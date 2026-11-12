<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * SEO behaviour added with config/seo_pages.php and config/tour_slug_redirects.php.
 * Read-only GET requests against the configured database (no data is written).
 */
class SeoPagesTest extends TestCase
{
    private function titleOf(string $html): string
    {
        preg_match('/<title>(.*?)<\/title>/s', $html, $m);
        return html_entity_decode(trim($m[1] ?? ''), ENT_QUOTES);
    }

    private function metaRobots(string $html): string
    {
        preg_match('/<meta name="robots" content="([^"]*)"/', $html, $m);
        return $m[1] ?? '';
    }

    private function canonical(string $html): string
    {
        preg_match('/<link rel="canonical" href="([^"]*)"/', $html, $m);
        return html_entity_decode($m[1] ?? '', ENT_QUOTES);
    }

    public function test_config_titles_are_unique_and_short(): void
    {
        $titles = [];
        foreach (['tours', 'activities', 'trekking', 'locations', 'posts', 'listings'] as $group) {
            foreach (config("seo_pages.$group") as $key => $entry) {
                if (isset($entry['title'])) {
                    $this->assertLessThanOrEqual(60, mb_strlen($entry['title']), "$group/$key title too long");
                    $titles[] = $entry['title'];
                }
                if (isset($entry['description'])) {
                    $this->assertLessThanOrEqual(160, mb_strlen($entry['description']), "$group/$key description too long");
                }
            }
        }
        $this->assertSame(count($titles), count(array_unique($titles)), 'Duplicate SEO titles in config/seo_pages.php');
    }

    public function test_redirect_map_has_no_chains_or_loops(): void
    {
        $map = config('tour_slug_redirects');
        $this->assertCount(5, $map);
        foreach ($map as $old => $new) {
            $this->assertNotSame($old, $new, "Redirect loop for $old");
            $this->assertArrayNotHasKey($new, $map, "Redirect chain: $new is also an old slug");
        }
    }

    public function test_missing_legacy_slug_301s_to_its_replacement(): void
    {
        config(['tour_slug_redirects.legacy-test-slug-for-phpunit' => 'agafay-desert-tour-from-marrakech']);

        $this->get('/tours/legacy-test-slug-for-phpunit')
            ->assertStatus(301)
            ->assertRedirect(route('front.tours.show', 'agafay-desert-tour-from-marrakech'));
    }

    public function test_unknown_tour_slug_is_404(): void
    {
        $this->get('/tours/this-tour-does-not-exist-xyz')->assertStatus(404);
    }

    public function test_unknown_url_is_a_real_404(): void
    {
        $this->get('/this-page-does-not-exist-xyz')->assertStatus(404);
    }

    public function test_city_listing_pages_have_unique_seo(): void
    {
        $urls = [
            '/tours'                                   => '|',
            '/tours/multi-day'                         => 'multi_day|',
            '/tours/day-trips'                         => 'day_trip|',
            '/tours/multi-day/from/marrakech-morocco'  => 'multi_day|marrakech-morocco',
            '/tours/day-trips/from/marrakech-morocco'  => 'day_trip|marrakech-morocco',
            '/tours/multi-day/from/fes-morocco'        => 'multi_day|fes-morocco',
            '/tours/day-trips/from/fes-morocco'        => 'day_trip|fes-morocco',
            '/tours/multi-day/from/casablanca-morocco' => 'multi_day|casablanca-morocco',
            '/tours/day-trips/from/casablanca-morocco' => 'day_trip|casablanca-morocco',
            '/tours/multi-day/from/tangier-morocco'    => 'multi_day|tangier-morocco',
        ];
        $seen = [];
        foreach ($urls as $url => $key) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertSame(config("seo_pages.listings.$key.title"), $this->titleOf($html), $url);
            $this->assertStringContainsString(e(config("seo_pages.listings.$key.h1")), $html, "$url H1");
            $this->assertSame('index, follow', $this->metaRobots($html), $url);
            $this->assertSame(url($url), $this->canonical($html), "$url canonical");
            $seen[] = $this->titleOf($html);
        }
        $this->assertSame(count($seen), count(array_unique($seen)));
    }

    public function test_thin_and_empty_listings_are_noindexed(): void
    {
        foreach (['/tours/multi-day/from/agadir-morocco', '/tours/multi-day/from/chefchaouen-morocco',
                  '/tours/multi-day/from/ouarzazate-morocco', '/tours/multi-day/from/morocco',
                  '/tours/day-trips/from/tangier-morocco'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertSame('noindex, follow', $this->metaRobots($html), $url);
        }
    }

    public function test_unknown_city_listing_is_404(): void
    {
        $this->get('/tours/multi-day/from/not-a-real-city')->assertStatus(404);
    }

    public function test_filtered_listing_is_noindexed_with_clean_canonical(): void
    {
        $html = $this->get('/tours?sort_by=price_low_high')->assertOk()->getContent();
        $this->assertSame('noindex, follow', $this->metaRobots($html));
        $this->assertSame(url('/tours'), $this->canonical($html));
    }

    public function test_pagination_has_self_canonical_and_page_title(): void
    {
        $html = $this->get('/tours?page=2')->assertOk()->getContent();
        $this->assertSame('index, follow', $this->metaRobots($html));
        $this->assertSame(url('/tours') . '?page=2', $this->canonical($html));
        $this->assertStringContainsString('Page 2', $this->titleOf($html));
    }

    public function test_product_pages_use_config_seo(): void
    {
        $cases = [
            ['/tours/agafay-desert-tour-from-marrakech', 'tours.agafay-desert-tour-from-marrakech'],
            ['/activities/agafay-desert-tour-from-marrakech-luxury-camp', 'activities.agafay-desert-tour-from-marrakech-luxury-camp'],
            ['/trekking/3-days-berber-villages-trek', 'trekking.3-days-berber-villages-trek'],
        ];
        foreach ($cases as [$url, $key]) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertSame(config("seo_pages.$key.title"), $this->titleOf($html), $url);
            $this->assertStringContainsString(e(config("seo_pages.$key.h1")), $html, "$url H1");
        }
        // The two Agafay pages must not share a title any more.
        $this->assertNotSame(
            config('seo_pages.tours.agafay-desert-tour-from-marrakech.title'),
            config('seo_pages.activities.agafay-desert-tour-from-marrakech-luxury-camp.title')
        );
    }

    public function test_location_pages_use_config_seo_and_catch_all_is_noindexed(): void
    {
        $html = $this->get('/locations/agadir-morocco')->assertOk()->getContent();
        $this->assertSame(config('seo_pages.locations.agadir-morocco.title'), $this->titleOf($html));

        $html = $this->get('/locations/morocco')->assertOk()->getContent();
        $this->assertSame('noindex, follow', $this->metaRobots($html));
    }
}
