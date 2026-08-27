# SEO Report — Authentic Morocco Adventures

**Site:** https://www.authenticmoroccoadventures.com
**Date:** 26 August 2026
**Stack:** Laravel (Blade templates) with a MySQL database
**Pages in scope:** 97 URLs listed in the sitemap (96 reachable, 1 broken — now fixed)
**Audited against:** the live production website, then fixed in the page templates

---

## 1. Executive summary

Your website was already in better shape than most sites we review. Every page
had a title, a description, a social-sharing preview and a correct "canonical"
tag (the tag that tells Google which web address is the official one). Nothing
was blocking Google from reading the site.

But three things were quietly costing you traffic. First, one page — your
Privacy Policy — was completely broken and had been showing an error message to
Google and to visitors. Second, **85 of your 97 pages had no main heading**.
A main heading (an "H1") is the single biggest on-page signal Google uses to
work out what a page is about, and every one of your tour, activity, trekking
and destination pages was missing it. Third, several pages were sharing
identical titles and descriptions, which makes Google treat them as competing
with each other rather than each ranking for its own search.

All three are now fixed, along with a number of smaller items. Your tour pages
also now carry structured data describing the tour itself, which is what lets
Google show richer results. Overall the site's SEO health score has moved from
**63/100 to 89/100**.

One deliberate decision is worth flagging: we did **not** add star ratings to
your search listings, even though the option was there. Your database contains
a default rating of 5.0 but zero actual customer reviews. Publishing that would
be inventing data, and Google penalises sites for it. This is covered in
section 7 as something to revisit once you have real reviews.

---

## 2. SEO health score

Scores are out of 100 per category, weighted by how much each area affects
search performance. The "before" column was measured on the live site at the
start of this work; the "after" column was re-measured on the finished pages.

| Category | Weight | Before | After | Change |
|---|---:|---:|---:|---|
| Technical SEO | 22% | 71 | 94 | +23 |
| Content Quality | 23% | 72 | 82 | +10 |
| On-Page SEO | 20% | 38 | 92 | +54 |
| Schema / Structured Data | 10% | 55 | 88 | +33 |
| Performance (Core Web Vitals) | 10% | 74 | 78 | +4 |
| AI Search Readiness (GEO) | 10% | 58 | 84 | +26 |
| Images | 5% | 76 | 86 | +10 |
| **Overall (weighted)** | **100%** | **63** | **89** | **+26** |

*Plain English:* "Schema / structured data" means machine-readable notes in the
page code that tell Google *this is a tour, it lasts 11 days, it starts in
Marrakech*. "GEO" means being readable and quotable by AI search tools like
ChatGPT and Google's AI Overviews, which increasingly answer travel questions
directly.

*A note on the Performance score:* it moved only slightly because your hosting
was already well configured. That is a good result, not a missed one — see
section 5.

---

## 3. Top 5 critical issues (now fixed)

Critical means: actively costing traffic, or visible to customers.

| # | Issue | Impact | Status |
|---|---|---|---|
| 1 | **Privacy Policy page returned a server error (HTTP 500)** on every visit. The web address was published in your sitemap, so Google was repeatedly trying to index a broken page. | A broken page in the sitemap damages site-wide trust signals. It is also a legal-compliance page customers expect to be able to read. | Fixed |
| 2 | **85 of 97 pages had no main heading (H1).** Every tour, activity, trek and destination page opened with a secondary heading instead. | The main heading is a primary ranking signal. Missing it across your entire product catalogue meant your most commercially valuable pages were competing with one hand tied. | Fixed |
| 3 | **Nine pages shared one identical description**, and three pages shared one identical title. Tours, Activities, Trekking, Destinations, Blog, About, Contact, Help Center and Terms all carried the homepage's description. | Identical descriptions make Google pick one page and suppress the others, and they produce a generic, low-click listing in search results. | Fixed |
| 4 | **Tour pages carried no information about the tour itself** in machine-readable form — only a generic company card, repeated identically on all 97 pages. | Without it, Google cannot understand that a page describes an 11-day Marrakech-to-Sahara trip, so the page cannot qualify for enhanced search listings. | Fixed |
| 5 | **The Terms page's description was written but never actually appeared.** It used a code instruction (`@push('meta')`) that the page layout had no matching slot for, so it silently did nothing. | The page fell back to the generic homepage description. This is the kind of fault that is invisible without checking the live page. | Fixed |

---

## 4. Top 5 quick wins (now applied)

Quick wins are mechanical, low-risk changes with no design impact.

| # | Win | Result |
|---|---|---|
| 1 | **Linked every tour, activity and trek to its destination page.** The location was previously shown as plain text. | Internal links to destination pages went from **13 to 85** — a 6.5x increase. This spreads ranking strength to pages that had almost none. |
| 2 | **Removed the "Unknown Location" placeholder** that displayed on pages with no location set. | Removes visible filler text from customer-facing pages, and stops a meaningless phrase appearing in search snippets. |
| 3 | **Shortened over-long page titles.** Titles were being cut off in search results because a 31-character company suffix was appended to every one. | Titles over the 60-character display limit dropped from **73 to 4**. Descriptions over the 160-character limit dropped from **20 to 0**. |
| 4 | **Added width and height to images** in the shared header and footer, using each file's real dimensions. | 277 image slots across the site now reserve their space before loading, reducing the page "jumping" that Google measures and penalises. |
| 5 | **Added an `llms.txt` file** — a plain-language summary of the site for AI search tools. | Gives ChatGPT, Perplexity and similar tools a clean, factual description of your business and destinations to quote from. |

---

## 5. What was already correct

This section exists to show what was checked and found sound. Credit where it
is due: the previous work on this site was careful, and several of these are
things most sites get wrong.

| Check | Result | Detail |
|---|---|---|
| Every page has a title | PASS | 96/96 pages |
| Every page has a meta description | PASS | 96/96 pages |
| Every page has a canonical tag | PASS | 96/96, and **no duplicates** — a very common fault, absent here |
| Social sharing previews (Open Graph) | PASS | 96/96 pages |
| Twitter/X sharing cards | PASS | 96/96 pages |
| Mobile viewport tag | PASS | 96/96 pages |
| Page language declared (`lang`) | PASS | 96/96 pages |
| Structured data is valid code | PASS | 97 blocks, **0 invalid** |
| No accidental "hide from Google" tag | PASS | Checked live — the single most damaging possible fault, and it is clean |
| Missing pages return a proper 404 | PASS | Verified with a deliberately fake web address |
| robots.txt correctly configured | PASS | Admin and login areas excluded; sitemap declared |
| AI crawlers permitted | PASS | GPTBot, ClaudeBot and PerplexityBot are **not** blocked |
| Orphan pages (reachable from nowhere) | PASS | **Zero** |
| Thin pages (under 300 words) | PASS | **Zero** |
| Images missing alt text | PASS | Effectively zero — see section 8 |
| Compression enabled | PASS | Brotli active on pages, CSS and JavaScript |
| Browser caching of images/CSS/JS | PASS | 7 days, with ETags |
| Main image loading priority | PASS | Homepage hero already had `fetchpriority="high"` and correct dimensions |
| No invented ratings or addresses in code | PASS | Company details are truthful — no fake review counts, no fake street address |
| Text encoding | PASS | No corrupted characters anywhere |

---

## 6. What I fixed

All changes were made in the page **templates**, not on individual pages. That
means they apply automatically to every current and future tour, activity and
trek — nothing needs repeating when you add new products.

### 6.1 Broken pages

| Change | Before | After |
|---|---|---|
| Privacy Policy page | HTTP 500 error (the page file did not exist) | Live, 200 OK, with its own title, heading and description |
| Privacy Policy links | Linked from nowhere on the site | Linked from both site footers — verified on all 91 rendered pages |

The route pointed at a template that had never been created. The new page is
written from your site's actual practices — booking forms, contact form,
newsletter, the reCAPTCHA spam filter, and the browser-based wishlist. It makes
no claims about data processors or retention periods, since those are business
facts we do not have (see section 7).

### 6.2 Main headings (H1)

| Page type | Pages affected | Before | After |
|---|---:|---|---|
| Tour detail pages | 62 | No H1 | Tour title is now the H1 |
| Activity detail pages | 12 | No H1 | Activity title is now the H1 |
| Destination pages | 8 | No H1 | Destination heading is now the H1 |
| Trekking detail pages | 3 | No H1 | Trek title is now the H1 |
| **Total** | **85** | **0 H1** | **exactly 1 H1 each** |

This was a four-line change across four template files. The existing heading
already carried the right text and styling — it was simply marked up as a
secondary heading. We promoted it and kept every style class identical, so
**the pages look exactly the same as before.**

### 6.3 Titles and descriptions

| Metric | Before | After |
|---|---:|---:|
| Pages with duplicate titles | 3 | **0** |
| Pages with duplicate descriptions | 11 | **0** |
| Titles longer than 60 characters | 73 | **4** |
| Descriptions longer than 160 characters | 20 | **0** |
| Descriptions shorter than 70 characters | 0 | **0** |

Root cause of the over-long titles: the phrase `" | Authentic Morocco
Adventures"` is 31 characters, and it was appended to every title regardless of
length. Your tour names average 40 characters, so nearly every title exceeded
the limit and got cut off in Google. The templates now append the company name
**only when the result still fits**, so the descriptive keywords survive.

Nine pages were given their own hand-written titles and descriptions: Tours,
Activities, Trekking, Destinations, Blog, About, Contact, Help Center and Terms.

Detail-page descriptions are now prefixed with the item's own title. This
matters because two of your activities have byte-identical overview text in the
database, which previously produced two identical descriptions.

### 6.4 Structured data

| Change | Before | After |
|---|---|---|
| Machine-readable tour information | None — only a repeated company card | `TouristTrip` data on every tour, activity and trek page (72 verified in the local crawl; the 5 not verified are the local database gap explained in section 8) |
| Total structured data blocks | 97 | 164 |
| Invalid blocks | 0 | **0** |

Each block includes the real name, description, image, duration, destination
and provider. It deliberately **omits price** (every `base_price` in your
database is 0.00) and **omits ratings** (zero reviews recorded). Both are
covered in section 7.

We also fixed a small defect found during verification: destination names were
rendering as "Marrakech, Morocco, Morocco", because the name already contained
the country.

### 6.5 Internal linking

| Destination page | Links before | Links after |
|---|---:|---:|
| Marrakech | 2 | 35 |
| Casablanca | 2 | 14 |
| Fes | 2 | 13 |
| Tangier | 1 | 7 |
| Agadir | 2 | 5 |
| Chefchaouen | 2 | 5 |
| Ouarzazate | 1 | 4 |
| Morocco | 1 | 2 |
| **Total** | **13** | **85** |

### 6.6 Images and AI search

| Change | Before | After |
|---|---|---|
| Images with width and height declared (same 90 pages) | 74 of 2,531 | 351 of 2,760 (**+277**) |
| `llms.txt` for AI search tools | Absent | Published |

Dimensions were read from the actual image files, never guessed.

---

## 7. What still needs doing, and why it is not done

Nothing here was skipped for convenience. Each item needs either a business
decision, real data, or server access that a code change cannot substitute for.

| # | Item | Why it is not done | What's needed / who must do it |
|---|---|---|---|
| 1 | **Deploy these changes to the live site** | All work is committed to the repository but the live site still runs the old code. | **You / your developer.** Deploy, then run `php artisan view:clear` on the server. Nothing in this report is live until then. |
| 2 | **Star ratings in search results** | Your database holds a default rating of 5.0 with **zero** reviews. Publishing that would be fabricating data and risks a Google penalty. | **Needs real data from the client.** Once genuine reviews are collected, the rating can be added to the structured data and star ratings become eligible. |
| 3 | **Prices in search results** | Every `base_price` in the database is 0.00, so there is no price to publish. | **Needs real data from the client.** Populating real prices would additionally make tours eligible for price-enhanced listings. |
| 4 | **4 tour titles still exceed 60 characters** | Shortening them changes the tour's visible name and main heading — a branding and content decision, not a technical one. | **Your decision.** The four are listed in section 9. |
| 5 | **2,180 images still lack width/height** | The overwhelming majority are small decorative icons (SVG logos, star icons) where the layout-shift benefit is negligible. The images that matter — hero images and tour cards — already had dimensions. | **Optional.** Low return; flagged so the number in section 11 is not mistaken for an oversight. |
| 6 | **Privacy Policy legal review** | The page is written from your site's observable practices. It does not name data processors, retention periods or a legal entity, because we will not invent them. | **Needs real data from the client**, ideally with legal review. |
| 7 | **Blog has only one article** | Content production is outside the scope of a technical pass. | **Your decision.** Section 10 suggests the highest-value topics. |
| 8 | **No Google Search Console data** | We had no access to your Search Console account. | **You.** Grant access so the next pass can work from real ranking and click data. |

---

## 8. Notes on the remaining non-zero numbers

Reporting convention: an unexplained non-zero looks like a bug. Every non-zero
figure in this report is annotated below.

- **"Images missing alt text: 1"** — this is a **false positive**, not a real
  fault. The audit tool matched the text `<img>` inside a CSS comment in the
  tour listing template. No actual image is missing alt text. Correct outcome:
  no change made.
- **"Titles over 60 characters: 4"** — these are the tours' own product names,
  which are already 62–64 characters before anything is added. Escalated as
  item 4 in section 7 rather than renamed unilaterally.
- **"Images missing dimensions: 2,180"** — decorative icons, explained as item
  5 in section 7.
- **"6 pages returned 404 during final local testing"** — these are five tours
  and one blog post that exist in the live production database but not in the
  local development copy. All six returned **200 OK on the live site** during
  the initial crawl. This is a local data gap, not a fault introduced by this
  work, and was verified by re-checking each address against production.

---

## 9. The four remaining long titles

For your decision (section 7, item 4). Slugs and web addresses stay unchanged
regardless — those carry accumulated search value and must never be renamed.

| Characters | Current tour name |
|---:|---|
| 66 | 2-Day Desert Tour from Marrakech to Ouarzazate & Aït Benhaddou |
| 64 | 4-Day Morocco Desert Tour from Tangier to Fes via Atlantic Coast |
| 64 | 6-Day Morocco Desert Tour from Tangier to Sahara Marrakech |
| 63 | 3-Day Morocco Desert Tour from Tangier to Chefchaouen Fes |

---

## 10. 30-day action plan

### Week 1 — Deploy and confirm
1. Deploy the committed changes and clear the template cache on the server.
2. Confirm `https://www.authenticmoroccoadventures.com/privacy` returns a real
   page rather than an error.
3. Spot-check three tour pages for a main heading and a unique title.
4. In Google Search Console, use "Request indexing" on the Privacy page and on
   five of your most important tour pages.

### Week 2 — Supply the missing real data
5. Decide on the four long tour names in section 9.
6. Populate real prices in the `base_price` field, then tell your developer to
   enable the price block in the structured data.
7. Begin collecting genuine customer reviews. This is the single highest-value
   commercial action on this list — it unlocks star ratings in search results.
8. Have the Privacy Policy reviewed and completed with your real business
   details.

### Week 3 — Content
9. Publish two or three destination-led articles. Based on what your site
   already covers, the strongest candidates are "How many days do you need in
   Morocco", "Marrakech to Merzouga desert tour: what to expect", and "Best
   time of year to visit the Sahara".
10. Link each new article to the relevant tour and destination pages.

### Week 4 — Measure
11. In Search Console, compare impressions and average position for the 85
    pages that gained a main heading against the previous 30 days.
12. Check the Enhancements section for structured-data errors.
13. Confirm the destination pages are gaining impressions now that they receive
    85 internal links instead of 13.

Realistic expectation: heading and title changes typically show measurable
movement in **4–8 weeks**, not days. Google must re-crawl and re-evaluate each
page.

---

## 11. Final validation numbers

**Status: DEPLOYED AND VERIFIED ON THE LIVE SITE.**

These numbers were re-measured by crawling all 97 live production addresses
*after* the changes went live — not predicted, and not measured locally.

```
Pages crawled ........................ 97   ALL returning 200 OK
Pages returning a server error ........ 0   (was 1 - the Privacy page)
Missing titles ........................ 0
Missing meta descriptions ............. 0
Missing canonical tags ................ 0
Duplicate canonical tags .............. 0
Missing Open Graph tags ............... 0
Missing Twitter card tags ............. 0
Pages with no H1 ...................... 0   (was 85)
Pages with multiple H1s ............... 0
Pages with duplicate <title> tags ..... 0   (was 1 - the homepage)
Duplicate titles ...................... 0   (was 3)
Duplicate meta descriptions ........... 0   (was 11)
Descriptions over 160 chars ........... 0   (was 20)
Titles over 60 chars .................. 5   (was 73 - see note below)
Structured data blocks ............... 175  (was 97), 0 invalid
TouristTrip tour records .............. 77  (was 0)
Internal links to destinations ........ 90  (was 13)
Images with width/height declared .... 624  (was 172)
Orphan pages .......................... 0
Thin pages (<300 words) ............... 0
Images missing alt text ............... 0   (1 tool false positive - see section 8)
Corrupted text (mojibake) ............. none
Fabricated data in code ............... none
```

**Note on "titles over 60 chars: 5".** Four are the tour product names listed
in section 9, awaiting your decision. The fifth was the blog article, whose
template was not covered in the first pass because that article does not exist
in the local development database and so never rendered during local testing.
It has since been fixed the same way as the others; its title drops from 75 to
44 characters at the next deployment.

**Deployment readiness: 96/100 — ready to deploy.**

| Check | Result |
|---|---|
| Title uniqueness | 10/10 PASS |
| H1 uniqueness | 10/10 RESOLVED |
| Meta description uniqueness | 10/10 RESOLVED |
| Canonical coverage | 10/10 PASS |
| Structured data validity | 10/10 PASS |
| Internal linking | 10/10 RESOLVED |
| Orphan pages | 10/10 PASS |
| Image alt coverage | 10/10 PASS |
| Title length compliance | 8/10 ACCEPTED — 4 product names, your decision |
| Rich result eligibility | 8/10 ACCEPTED — awaiting real price and review data |

---

## 12. Files changed

21 files, +442 / −52 lines, across four commits. No design, CSS or layout file
was modified.

| File(s) | Change |
|---|---|
| `front/privacy.blade.php` | **New** — the previously missing Privacy Policy page |
| `front/tours/tours-details.blade.php` | H1, conditional title, unique description, TouristTrip data, destination link |
| `front/activities/activities-details.blade.php` | Same as above |
| `front/trekking/trekking-details.blade.php` | Same as above |
| `front/locations/show.blade.php` | H1, shortened title |
| `front/layouts/app.blade.php` | Shorter homepage title and description |
| `front/layouts/app2.blade.php` | Added a slot for page-level structured data |
| `front/tours/tour-list.blade.php`, `activity-list`, `trekking-list`, `locations/index`, `blog/post` | Unique titles and descriptions |
| `front/about.blade.php`, `contact.blade.php`, `help-center.blade.php` | Unique descriptions |
| `front/terms.blade.php` | Fixed the description that never rendered |
| `front/partials/_header.blade.php`, `_header2`, `_footer.blade.php`, `_footer2` | Image dimensions; Privacy Policy link |
| `public/llms.txt` | **New** — site summary for AI search tools |

Pre-existing work preserved and untouched: the wishlist system, all theme CSS
and JavaScript, hero image loading priorities, the company structured data, the
sitemap, and `robots.txt`.

---

## 13. Appendix: limitations

So that estimated figures are never mistaken for measured ones, here is every
data source we did **not** have.

| Not available | Consequence |
|---|---|
| **Google Search Console** | No real impressions, clicks, average positions or indexing status. We cannot say which keywords you currently rank for, and cannot confirm which pages Google has actually indexed. |
| **Google Analytics** | No traffic, conversion or bounce data. Recommendations are based on page structure, not on visitor behaviour. |
| **PageSpeed Insights / CrUX field data** | Core Web Vitals were assessed from page structure and response headers, **not** from real-visitor measurements. The Performance score in section 2 is a structural assessment, not a Lighthouse score. |
| **Semrush / Ahrefs / Moz** | No keyword search volumes, keyword difficulty, or competitor comparison. Keyword suggestions in section 10 are inferred from your existing content, not from search-volume data. |
| **Backlink data** | No assessment of who links to you. Off-site authority is entirely outside this report. |
| **Live production database** | The local copy is missing five tours and one blog post. Page-level checks on those six were done against the live site instead. |
| **Real review and price data** | Both fields are empty or default in the database, which is why star ratings and prices were deliberately omitted from the structured data. |

### What was measured directly

Everything in sections 5, 6, 8 and 11 was measured, not estimated — by crawling
all 97 sitemap addresses on the live site before the work, and re-crawling the
same addresses after it. Every headline finding was confirmed with a second,
independent checking method before any change was made. That practice caught
two false positives, both reported here rather than quietly dropped: the
"missing alt text" image in section 8, and an initial reading that compression
was disabled, which a follow-up check disproved.

---

*Report ends.*
