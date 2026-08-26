---
name: seo-project-adapter
description: >
  READ FIRST on any project. Detects the stack (static HTML, Laravel/Blade,
  WordPress, Next.js/React, Rails, Django, Astro…) and maps every command in
  the other SEO skills onto it. Defines the crawl-first rule for dynamic
  sites, where each SEO element lives per stack, and the template-vs-instance
  distinction that decides whether a finding is one bug or a thousand.
---

# SEO Project Adapter Skill

**Load this before any other SEO skill.** The other skills state their
detection logic in terms of files on disk. That is a *concrete instance* of a
general method, not the method itself. This skill maps it onto your stack.

---

## 0. The one rule that changes everything

> **On a dynamic site, audit the RENDERED OUTPUT. Fix the TEMPLATE.**

Static HTML is the degenerate case where those are the same file. Everywhere
else they are two different things, and conflating them causes both major
failure modes:

| Mistake | Result |
|---|---|
| Auditing templates instead of output | You miss every data-driven defect — a null `$product->meta_description` renders an empty tag your grep never sees |
| Fixing instances instead of templates | You fix one of 4,000 pages, and the next deploy reverts it |

**Corollary — the template-vs-instance rule:**

> One broken template = one bug affecting N pages. **Report it as one finding
> with a page count**, not N findings.

An audit that lists "4,000 pages missing canonical" when the truth is "one
layout is missing `<link rel=canonical>`" is technically accurate and
practically useless. State it as: *"`layouts/app.blade.php` omits the
canonical tag → affects 4,000 URLs."*

---

## 1. Detect the stack

```bash
# Run this first, in the project root
ls artisan composer.json package.json manage.py Gemfile wp-config.php \
   next.config.js nuxt.config.ts astro.config.mjs 2>/dev/null
test -d resources/views && echo "LARAVEL/BLADE"
test -d wp-content     && echo "WORDPRESS"
test -d app -a -f next.config.js && echo "NEXTJS"
test -d src/pages -o -d src/content && echo "ASTRO/VITE"
test -d app/views     && echo "RAILS"
test -d templates     && echo "DJANGO/JINJA/FLASK"
find . -maxdepth 2 -name '*.html' -not -path './node_modules/*' | head -5
```

Then answer these five questions before touching anything:

1. **Where does rendered HTML come from?** Files on disk, a server render, a
   build step, or client-side JS?
2. **Where do title/meta/canonical live?** A layout, per-view sections, a
   frontmatter block, an SEO package, or a DB column?
3. **What is the URL → source mapping?** A route file, file-based routing, or
   a DB slug?
4. **Is content in templates or in a database?**
5. **Does a build step exist?** If yes, the built output is what ships — audit
   that, not the source.

---

## 2. Where each SEO element lives, per stack

| Element | Static HTML | Laravel / Blade | WordPress | Next.js / React | Astro / 11ty | Rails | Django |
|---|---|---|---|---|---|---|---|
| `<title>` | in each file | `@section('title')` in child view | `wp_title()` / Yoast / RankMath | `metadata` export or `<Head>` | frontmatter `title` | `content_for :title` | `{% block title %}` |
| meta description | in each file | `@section('meta_description')` | SEO plugin field | `metadata.description` | frontmatter | `content_for` | block |
| canonical | in each file | layout, from `url()->current()` | plugin / `rel_canonical()` | `metadata.alternates.canonical` | layout + `Astro.url` | layout helper | block/context |
| JSON-LD | inline `<script>` | `@push('schema')` in child view | plugin or `wp_head` hook | `<script type="application/ld+json">` in layout | component | partial | `{% block %}` |
| nav / footer | synced partial | `layouts/app.blade.php` | `header.php`/`footer.php` | layout component | layout `.astro` | `application.html.erb` | `base.html` |
| internal links | hardcoded `href` | `route()` / `url()` | `get_permalink()` | `<Link href>` | `<a href>` | `link_to`/`_path` | `{% url %}` |
| robots/sitemap | static files | package or route | plugin | `app/robots.ts`, `sitemap.ts` | integration | gem/route | app/view |
| redirects | `.htaccess` | `routes/web.php` middleware | plugin / `.htaccess` | `next.config.js` redirects | host config | `routes.rb` | middleware |

**Golden rules per stack:**

- **Laravel:** titles/meta/schema go in the **child view**, never the shared
  layout — a hardcoded `<title>` in `layouts/app.blade.php` gives every page
  the same title. Canonicals go in the layout but generated
  (`{{ url()->current() }}`), never hardcoded. Internal links use **named
  routes** so slugs change in one place.
- **WordPress:** never hand-edit theme files for SEO that a plugin owns —
  you'll get two competing tags. Find out whether Yoast/RankMath/SEOPress is
  authoritative first.
- **Next.js:** App Router `metadata` export vs Pages Router `<Head>` are
  different systems; check which. Verify what the **server** sends, not what
  the browser shows after hydration.
- **Static:** if a sync script owns nav/footer, edit the source and re-run it;
  never edit 100 pages individually.

---

## 3. Getting an auditable corpus from a dynamic site

Every command in the other skills operates on "a set of HTML documents". Your
job here is to **produce that set**, then everything else works unchanged.

### Option A — Crawl the running site (preferred; ground truth)

```bash
# Start the app first (php artisan serve / npm run dev / docker compose up)
BASE=http://localhost:8000
mkdir -p /tmp/seo-crawl && cd /tmp/seo-crawl

wget --mirror --page-requisites=off --html-extension --convert-links=off \
     --reject-regex='\?(page|sort|filter|utm)' \
     --domains=localhost --no-parent "$BASE"
```

Or crawl the sitemap directly — faster and matches what you want indexed:

```bash
curl -s $BASE/sitemap.xml | grep -o '<loc>[^<]*</loc>' | sed 's/<\/\?loc>//g' > urls.txt
i=0; while read u; do
  i=$((i+1)); curl -sL "$u" -o "$(printf '%04d' $i).html"
  echo "$(printf '%04d' $i).html  $u" >> manifest.txt
done < urls.txt
```

**Keep `manifest.txt`.** It maps each captured file back to its URL — you need
it to report findings against URLs, and to trace them back to templates.

Then run every other skill's commands against `/tmp/seo-crawl`, unchanged.

⚠️ **Crawl production, or a build with production-like data.** A dev database
with 3 seeded rows will not reveal the duplicate-title problem that exists
across 4,000 real products.

### Option B — Render a representative sample

Full crawls are expensive on large sites. Sample **by template**, not
randomly:

```
For each distinct template/route pattern, capture:
  - 3 typical instances
  - 1 edge case (longest title, missing image, no description, empty relation)
```

20 templates × 4 = 80 pages catches nearly every template-level defect. It
will **not** catch data-level defects on the other 3,920 pages — so pair it
with the SQL checks in §5.

### Option C — Static export

```bash
npm run build && npx next export     # Next.js
npm run build                        # Astro/11ty/Hugo → dist/ or _site/
php artisan export                   # if a static exporter is configured
```

Audit the built output. **This is what ships** — audit it, not the source.

### Option D — Grep templates directly (limited)

Only valid for finding **structurally** missing elements — a layout with no
canonical tag at all. It cannot find data-driven defects. Never report
coverage percentages from template greps.

---

## 4. Translating the other skills' commands

The pattern is always: **replace the file-set with your rendered corpus.**

| Skill command assumes | Static | Dynamic equivalent |
|---|---|---|
| `find . -name '*.html'` | source files | `find /tmp/seo-crawl -name '*.html'` |
| `rglob('*.html')` | source tree | crawl dir, or a URL list fed through `requests` |
| `grep -c 'rel="canonical"' $f` | per page | per rendered page; then trace to the template |
| body-link graph (§internal-linking) | relative hrefs | crawled absolute URLs; resolve against `BASE` |
| `curl -sI $D/page.html` | live check | unchanged — **always hit the real URL** |
| word-count / Jaccard | source text | rendered text (templates have no content) |

Python corpus loader that works for both:

```python
import pathlib, re

def load_corpus(src):
    """Yield (identifier, html). src = crawl dir, or a file with URLs."""
    p = pathlib.Path(src)
    if p.is_dir():
        for f in p.rglob('*.html'):
            yield str(f), f.read_text(encoding='utf-8', errors='replace')
    else:
        import urllib.request
        for url in p.read_text(encoding='utf-8').split():
            try:
                with urllib.request.urlopen(url) as r:
                    yield url, r.read().decode('utf-8', 'replace')
            except Exception as e:
                print('FETCH FAIL', url, e)

# every other skill's analysis now runs over load_corpus(...)
```

### Tracing a rendered finding back to its template

This is the step that makes a dynamic audit actionable:

```bash
# Found a bad title on /products/blue-widget — which template emitted it?
grep -rn "Buy .* Online | " resources/views/     # the literal pattern
grep -rn "@section('title'"  resources/views/products/
php artisan route:list | grep products           # route → controller → view
```

Report as: **template path + affected URL count + one example URL.**

---

## 5. Database-driven content — the checks templates can't give you

On a CMS/e-commerce site most SEO defects live in **data**, not code. Run
these directly against the DB.

```sql
-- Duplicate titles (the #1 dynamic-site defect)
SELECT meta_title, COUNT(*) c FROM products
GROUP BY meta_title HAVING c > 1 ORDER BY c DESC;

-- Missing / empty meta
SELECT COUNT(*) FROM products WHERE meta_description IS NULL OR meta_description = '';

-- Over-length (measure RENDERED length — see seo-keyword-research §6)
SELECT id, slug, CHAR_LENGTH(meta_title) n FROM products
WHERE CHAR_LENGTH(meta_title) > 60 ORDER BY n DESC;

-- Thin content
SELECT id, slug, CHAR_LENGTH(description) n FROM products
WHERE CHAR_LENGTH(description) < 300 ORDER BY n;

-- Duplicate slugs / would-be duplicate URLs
SELECT slug, COUNT(*) c FROM products GROUP BY slug HAVING c > 1;

-- Orphans: published but not reachable from any category
SELECT p.id, p.slug FROM products p
LEFT JOIN category_product cp ON cp.product_id = p.id
WHERE cp.product_id IS NULL AND p.published = 1;
```

Laravel equivalents via tinker:

```php
php artisan tinker
>>> Product::selectRaw('meta_title, COUNT(*) c')->groupBy('meta_title')
      ->havingRaw('COUNT(*) > 1')->get();
>>> Product::whereNull('meta_description')->orWhere('meta_description','')->count();
>>> Product::whereRaw('CHAR_LENGTH(meta_title) > 60')->pluck('slug');
```

**The fallback-template trap.** A template like
`{{ $product->meta_title ?? $product->name . ' | Shop' }}` produces *valid but
duplicated* titles wherever the field is null. Grep will never flag it — only
a rendered-corpus duplicate check or the SQL above will. This is the single
most common dynamic-site SEO defect.

---

## 6. Applying edits — per stack

| Task | Static | Laravel | WordPress | Next.js |
|---|---|---|---|---|
| Fix one page's title | edit the file | edit that view's `@section('title')` | edit the post's SEO field | edit that route's `metadata` |
| Fix a whole page type | script over N files | edit **one** template | edit the template or plugin default | edit one layout/generator |
| Fix per-record data | n/a | migration or seeder | bulk-edit / WP-CLI | migration/script |
| Add schema | inject before `</head>` | `@push('schema')` in child view | `wp_head` hook or plugin | `<script>` in layout |
| Add canonical | per file | layout, `{{ url()->current() }}` | plugin default | `metadata.alternates.canonical` |

**Bulk data fix (Laravel migration — reversible, reviewable, deployable):**

```php
// database/migrations/xxxx_fix_duplicate_meta_titles.php
public function up(): void {
    Product::whereNull('meta_title')->orWhere('meta_title', '')
        ->chunkById(200, function ($products) {
            foreach ($products as $p) {
                $p->meta_title = Str::limit(
                    "{$p->name} — {$p->category->name}", 57, ''
                );
                $p->saveQuietly();   // don't fire observers/events
            }
        });
}
```

Rules for data fixes: `chunkById` not `all()`; `saveQuietly()` to avoid
triggering observers and audit logs; always write a `down()`; **run on a
staging copy first and diff the rendered output.**

---

## 7. Per-skill applicability

| Skill | Static | Laravel/dynamic | Notes for dynamic |
|---|---|---|---|
| seo-verification | ✅ as written | ✅ **more important** | "Repo ≠ production" becomes "template ≠ rendered ≠ production". Three states to diff. |
| seo-technical-audit | ✅ | ✅ via crawl | Redirects live in route files/middleware, not `.htaccess`. Watch for DB-driven redirect tables. |
| seo-keyword-research | ✅ | ✅ | Titles may come from DB columns — §5 SQL replaces the greps. Ownership map still per-URL. |
| seo-cannibalization | ✅ | ✅ **critical** | Templated sites cannibalize by construction. Run Jaccard on rendered output; check faceted/filter URLs. |
| seo-internal-linking | ✅ | ✅ via crawl | Links are generated — fix the generator, not the output. Watch paginated/faceted link explosion. |
| seo-schema | ✅ | ✅ | Inject in child views. Truthfulness checks run against **rendered** JSON-LD. |
| seo-geo-ai-search | ✅ | ✅ | ⚠️ **JS-rendered content may be invisible to AI crawlers.** Verify with `curl`, not the browser. |
| seo-content-strategy | ✅ | ✅ | DO-NOT-TOUCH list gains: don't edit migrations/seeders during a content pass. |
| seo-performance | ✅ | ✅ | Adds server-render time (TTFB), N+1 queries, hydration cost. Check query counts. |
| seo-reporting | ✅ | ✅ | Report template-level findings with affected-URL counts. |

---

## 8. Dynamic-only failure modes

Checks the other skills don't cover because static sites can't have them:

1. **Faceted-navigation index bloat.** `?color=red&size=xl` generating
   millions of crawlable URLs.
   ```bash
   curl -s $BASE/sitemap.xml | grep -c '<loc>'
   curl -s "$BASE/products?color=red" -o /dev/null -w '%{http_code}\n'
   # then check for <meta robots noindex> or rel=canonical on facets
   ```
   Fix: canonical to the unfaceted URL, `noindex` on filter combos, or block
   in robots.txt.

2. **Pagination.** Page 2+ must self-canonicalize (**not** canonical to page
   1) and carry unique titles.

3. **Session IDs / tracking params in URLs** creating infinite duplicates.

4. **JS-only content.** Verify server output:
   ```bash
   curl -s "$BASE/products/x" | grep -c "product description text"
   # 0 = content is client-rendered = invisible to many crawlers and most AI bots
   ```

5. **Soft 404s.** A deleted product returning 200 with an empty template.
   ```bash
   curl -s -o /dev/null -w '%{http_code}\n' "$BASE/products/definitely-not-real"
   # must be 404, not 200
   ```

6. **Staging leakage** — `noindex` from a staging env var shipping to prod:
   ```bash
   curl -s $BASE/ | grep -i 'noindex'    # must be empty on production
   curl -sI $BASE/ | grep -i 'x-robots'
   ```
   **Check this first on any dynamic site.** It is the single most damaging
   and most common dynamic-site SEO bug.

7. **N+1 queries** destroying TTFB. Laravel Debugbar / `DB::listen`.

8. **Preview/draft URLs** indexed.

---

## 9. Encoding and safety, per stack

- **Static HTML on Windows:** `[System.IO.File]::ReadAllText/WriteAllText`
  with `[System.Text.UTF8Encoding]::new($false)`. Never
  `Get-Content`/`Set-Content`.
- **Blade/PHP/Python/Ruby templates:** UTF-8 by convention; standard writes
  are fine. Still verify no mojibake after bulk edits.
- **DB content:** confirm the connection charset is `utf8mb4`, not `latin1` —
  wrong charset produces mojibake no file-level check will catch.
- **Blade escaping:** `{{ }}` escapes; use `{!! !!}` only for trusted
  pre-built HTML (e.g. a JSON-LD string you generated). Never `{!! !!}` on
  user input.
- **Version control:** commit before bulk edits regardless of stack.
- **Derived artifacts:** built output, compiled views (`php artisan
  view:clear`), CDN cache, and `.br`/`.gz` siblings all need regenerating.

---

## 10. Checklist before running any other SEO skill

- [ ] Stack detected; the five §1 questions answered
- [ ] Determined where title/meta/canonical/schema actually live
- [ ] **Production `noindex` leak checked** (§8.6)
- [ ] Rendered corpus obtained (crawl / sample / build output), not templates
- [ ] `manifest.txt` maps captured files → URLs
- [ ] Corpus is production-like data, not a 3-row dev seed
- [ ] DB-level checks planned where content is data-driven (§5)
- [ ] Know how to trace a rendered finding back to its template
- [ ] Findings will be reported as **template + affected URL count**
- [ ] Edit target identified per fix: template vs data vs config
