---
name: seo-schema
description: >
  Deploy, audit and validate Schema.org JSON-LD at scale across a site.
  Covers per-archetype schema selection, required properties, the
  visible-content rule, NAP consistency, and the never-fabricate-data rule.
  Includes the exact detection commands for invisible schema, inconsistent
  review counts, and invalid JSON-LD.
---

# SEO Schema Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

Structured data is the highest-leverage, lowest-risk SEO work on most
sites — **provided it is truthful and visible.** Untruthful schema is the
single fastest way to earn a manual action.

---

## 0. Two rules that override everything else

### Rule 1 — Never fabricate data

Never invent a rating, review count, price, coordinate, date, award,
certification, or address. Not even a plausible-looking one.

If real data is unavailable: **omit the property.** A missing
`aggregateRating` costs a star snippet. A fake one can cost the site.

> Real incident on the reference project: homepage schema claimed
> `"reviewCount": "200"` when the real Google Business Profile had **20**.
> Meanwhile 65 files carried `"streetAddress": "Marrakech"` — a city, not an
> address. Both were fabricated by an earlier pass. Both were corrected to
> real GBP data across 123 pages.

### Rule 2 — Schema must match visible page content

Every claim in JSON-LD must be verifiable by a human reading the rendered
page. Google's structured-data policy requires it, and invisible schema
produces **no rich result anyway** — it is pure risk with zero upside.

> Real incident: **69 pages** carried `FAQPage` schema with no visible FAQ
> content anywhere in the body. The fix (rendering the Q&A visibly on 57
> tour pages) was the highest-leverage change of the entire pass — it
> simultaneously fixed the violation, created real on-page content, and
> unlocked AI-citation eligibility.

---

## 1. Choose schema by page archetype

Detect the archetype, then apply the matching set. Do not put every type on
every page.

| Archetype | Required | Recommended |
|---|---|---|
| **Site-wide** (all pages) | `Organization` or a subtype (`LocalBusiness`, `TravelAgency`, `Store`) | `sameAs` social profiles |
| **Homepage** | Organization + `WebSite` (+`SearchAction`) | `AggregateRating` (only if real) |
| **Product / service detail** | `Product`, `Service`, `TouristTrip`, `Course`… + `Offer` | `BreadcrumbList`, `FAQPage`, `AggregateRating` |
| **Hub / listing / category** | `ItemList`, `BreadcrumbList` | `FAQPage`, `CollectionPage` |
| **Blog post / article** | `BlogPosting` or `Article`, `BreadcrumbList` | `FAQPage`, `Person` author |
| **Author bio page** | `Person` (+`ProfilePage`) | `knowsAbout`, `sameAs` |
| **Contact / about** | `Organization` with full NAP | `ContactPoint` |
| **Every page except home** | `BreadcrumbList` | — |

**`AggregateRating` belongs on an Organization, Product, or Service — never
on a `Person`.** That combination is invalid and was found fabricated on the
reference project's author page.

---

## 2. Core patterns

### Organization / LocalBusiness — site-wide

Use the **most specific** applicable subtype. NAP must be byte-identical to
the Google Business Profile.

```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "EXACT legal/GBP name",
  "url": "https://www.example.com/",
  "logo": "https://www.example.com/assets/logo.png",
  "image": "https://www.example.com/assets/og.jpg",
  "telephone": "+1-555-0100",
  "email": "contact@example.com",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "REAL street address from GBP",
    "addressLocality": "City",
    "postalCode": "00000",
    "addressCountry": "US"
  },
  "foundingDate": "2012",
  "priceRange": "$$",
  "areaServed": { "@type": "Country", "name": "United States" },
  "sameAs": [
    "https://www.facebook.com/...",
    "https://www.instagram.com/..."
  ]
}
```

`geo` / `GeoCoordinates`: include **only** with the real GBP pin, 5-decimal
precision. Never approximate from a city centre.

### AggregateRating — only with real, visible numbers

```json
"aggregateRating": {
  "@type": "AggregateRating",
  "ratingValue": "4.9",
  "reviewCount": "20",
  "bestRating": "5"
}
```

The same `reviewCount` must appear in visible page text. One number,
site-wide, matching the real review platform.

### BreadcrumbList — every page except home

The last item has **no** `item` property (it is the current page).

```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.example.com/" },
    { "@type": "ListItem", "position": 2, "name": "Category", "item": "https://www.example.com/category/" },
    { "@type": "ListItem", "position": 3, "name": "This Page" }
  ]
}
```

Schema breadcrumbs should mirror a **visible** breadcrumb trail. Also verify
the visible trail's links aren't all pointing at `/` — a real bug found on
48 pages of the reference project.

### ItemList — hub and listing pages

Frequently missing. It was absent on 13/14 hub pages in the reference audit.

```json
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "url": "https://www.example.com/item-a/", "name": "Item A" },
    { "@type": "ListItem", "position": 2, "url": "https://www.example.com/item-b/", "name": "Item B" }
  ]
}
```

Order and count must match the visible listing.

### FAQPage — schema AND visible markup together

Never ship one without the other.

```html
<section class="faq">
  <h2>Frequently asked questions</h2>
  <h3>How long does it take?</h3>
  <p>Typically three to five business days…</p>
</section>
```

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "How long does it take?",
    "acceptedAnswer": { "@type": "Answer", "text": "Typically three to five business days…" }
  }]
}
```

Answer text in JSON-LD must match the visible answer. Questions must be
**page-specific** — templated identical FAQs across 70 pages is duplicate
content, not structured data.

### BlogPosting

```json
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "≤110 characters",
  "description": "…",
  "url": "https://www.example.com/blog/post/",
  "image": "https://www.example.com/assets/post.jpg",
  "datePublished": "2026-08-01",
  "dateModified": "2026-08-26",
  "author": { "@type": "Person", "name": "Real Name", "url": "https://www.example.com/team/real-name.html" },
  "publisher": {
    "@type": "Organization",
    "name": "Brand",
    "logo": { "@type": "ImageObject", "url": "https://www.example.com/assets/logo.png" }
  }
}
```

`author` must be a real, named person with a working profile URL — not
"Admin" or the brand name. Visible byline + date required.

### Person — author E-E-A-T

```json
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Real Name",
  "jobTitle": "Founder & Licensed Guide",
  "image": "https://www.example.com/assets/team/real-name.webp",
  "worksFor": { "@type": "Organization", "name": "Brand" },
  "knowsAbout": ["Topic A", "Topic B"],
  "hasCredential": {
    "@type": "EducationalOccupationalCredential",
    "credentialCategory": "Professional License",
    "identifier": "REAL-LICENSE-NUMBER"
  },
  "sameAs": ["https://www.linkedin.com/in/..."]
}
```

`image` must point to a file that **actually exists** — a broken `Person.image`
was a real finding. Verify:

```bash
grep -rhoP '"image":\s*"\K[^"]+' --include=*.html . | sort -u | while read u; do
  f=".${u#https://www.example.com}"
  [ -f "$f" ] || echo "MISSING: $u"
done
```

Never attach `AggregateRating` to a `Person`.

---

## 3. Audit commands

### Inventory

```bash
grep -rho '"@type": *"[A-Za-z]*"' --include=*.html . | sort | uniq -c | sort -rn
```

### Validity — every block must parse

```bash
python3 - <<'PY'
import json, re, pathlib
bad = tot = 0
for p in pathlib.Path('.').rglob('*.html'):
    t = p.read_text(encoding='utf-8', errors='replace')
    for m in re.findall(r'<script[^>]*application/ld\+json[^>]*>(.*?)</script>', t, re.S):
        tot += 1
        try: json.loads(m)
        except Exception as e:
            bad += 1; print('INVALID', p, str(e)[:90])
print(f'{tot} blocks, {bad} invalid')
PY
```

Target: **0 invalid**.

### Coverage gaps

```bash
# Pages with no JSON-LD at all
for f in $(find . -name '*.html' -not -path './vendor/*'); do
  grep -q 'application/ld+json' "$f" || echo "NO SCHEMA: $f"
done

# Non-home pages missing BreadcrumbList
for f in $(find . -name '*.html' -not -path './vendor/*'); do
  [ "$f" = "./index.html" ] && continue
  grep -q '"BreadcrumbList"' "$f" || echo "NO BREADCRUMB: $f"
done
```

### The invisible-schema violation set

```bash
grep -rl '"@type": *"FAQPage"' --include=*.html . | sort > /tmp/faq_schema.txt
grep -rl -iE 'class="[^"]*faq|<h[23][^>]*>[^<]*\?[[:space:]]*</h[23]>' --include=*.html . | sort > /tmp/faq_visible.txt
comm -23 /tmp/faq_schema.txt /tmp/faq_visible.txt   # must be empty
```

### Truthfulness sweep — run before every deploy

```bash
# Every distinct review/rating value site-wide (expect exactly one of each)
grep -rho '"\(reviewCount\|ratingCount\|ratingValue\)": *"\?[0-9.]*' --include=*.html . \
  | sort | uniq -c | sort -rn

# Address consistency (expect one)
grep -rh '"streetAddress"' --include=*.html . | sort | uniq -c | sort -rn

# Visible counts — must agree with schema
grep -rhoE '[0-9]{2,4}\+? (reviews|ratings)' --include=*.html . | sort | uniq -c

# Invalid: rating on a Person
grep -rl '"@type": *"Person"' --include=*.html . | xargs grep -l 'aggregateRating' 2>/dev/null
```

More than one distinct address or review count = defect.

---

## 4. Where to inject, per stack

| Stack | Inject JSON-LD in | Never in |
|---|---|---|
| Static HTML | before `</head>` per page | — |
| Laravel/Blade | `@push('schema')` in the **child view** | the shared layout (every page gets identical product schema) |
| WordPress | `wp_head` hook, or let the SEO plugin own it | theme files, if a plugin already emits it — you'll get two competing blocks |
| Next.js | `<script type="application/ld+json">` in the route's layout/page | — |
| Rails | `content_for :schema` in the view | `application.html.erb` for page-specific types |
| Django | `{% block schema %}` in the child template | `base.html` for page-specific types |

Site-wide `Organization` schema is the one type that **belongs** in the shared
layout. Everything page-specific belongs in the child view.

**Generate from real data, never hardcode:**

```blade
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Product',
    'name'     => $product->name,
    'url'      => url()->current(),
    // omit the property entirely when there is no real rating —
    // never emit a placeholder (see §0 Rule 1)
    ...($product->reviews_count > 0 ? ['aggregateRating' => [
        '@type'       => 'AggregateRating',
        'ratingValue' => (string) round($product->reviews_avg, 1),
        'reviewCount' => (string) $product->reviews_count,
    ]] : []),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
```

Note `json_encode` rather than a hand-built string — it escapes quotes in
product names, which is the most common cause of invalid JSON-LD on dynamic
sites. Use `{!! !!}` only because the payload is machine-generated; never
interpolate raw user input into a schema string.

⚠️ **The conditional above is the whole point.** A template that always emits
`aggregateRating` will render `"reviewCount": "0"` on every unreviewed
product — fabricated data at scale, generated by a well-meaning template.
Validate against the **rendered** output, not the template.

---

## 5. Bulk injection — safely

```python
import json, pathlib, re

def inject_schema(path, obj, marker='</head>'):
    """Insert a JSON-LD block before </head>. Idempotent by @type."""
    t = pathlib.Path(path).read_text(encoding='utf-8')
    if f'"@type": "{obj["@type"]}"' in t:
        return False                              # already present
    block = ('<script type="application/ld+json">\n'
             + json.dumps(obj, ensure_ascii=False, indent=2)
             + '\n</script>\n')
    i = t.rfind(marker)
    if i == -1:
        return False
    pathlib.Path(path).write_text(t[:i] + block + t[i:], encoding='utf-8', newline='\n')
    return True
```

Safety rules for bulk passes:

1. **Idempotent** — re-running must not duplicate blocks.
2. **Commit first.** Bulk edits go wrong; `git checkout` is the undo.
3. **Validate after** — re-run the parse check; expect 0 invalid.
4. **Check tag parity** — see the verification skill.
5. **UTF-8 explicitly.** Python: `encoding='utf-8'`. On Windows PowerShell
   use `[System.IO.File]::ReadAllText/WriteAllText` with
   `[System.Text.UTF8Encoding]::new($false)` — **never**
   `Get-Content`/`Set-Content`, which produce `â€"` mojibake.
6. **Regenerate `.br`/`.gz` siblings** after editing, or the server keeps
   serving pre-edit markup to real visitors.

---

## 6. External validation

- **Google Rich Results Test** — `https://search.google.com/test/rich-results`
  (eligibility; the authority on whether it earns a rich result)
- **Schema.org validator** — `https://validator.schema.org/` (correctness)
- **GSC → Enhancements** — post-deploy reality; watch for new errors 3–14
  days after shipping

Test one page per archetype, not every page.

---

## 7. Checklist

- [ ] Zero fabricated values (ratings, counts, prices, coordinates, addresses)
- [ ] One distinct NAP site-wide, matching the real GBP exactly
- [ ] Schema review counts match visible on-page counts
- [ ] No `AggregateRating` on a `Person`
- [ ] Every FAQ/Review/HowTo schema has visible on-page counterpart content
- [ ] FAQ questions are page-specific, not templated across the site
- [ ] `BreadcrumbList` on every non-home page; visible trail links are correct
- [ ] `ItemList` on every hub/listing page, matching the visible list
- [ ] Blog posts have real named author + working profile URL + visible byline
- [ ] Every schema `image`/`logo` URL resolves to a real file
- [ ] 100% of JSON-LD blocks parse
- [ ] Rich Results Test passes for one page per archetype
- [ ] `.br`/`.gz` siblings regenerated after bulk edits
