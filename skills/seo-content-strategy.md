---
name: seo-content-strategy
description: >
  Plan and produce SEO content that feeds commercial pages. Inventory-first
  topic selection, four-axis prioritization, hub-and-spoke cluster maps, a
  10-section content brief template, blog→money-page linking rules, and the
  site-wide editorial proofread method with its DO-NOT-TOUCH safety list.
---

# SEO Content Strategy Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

---

## 1. Topic selection — inventory-first, not keyword-first

The diagnosis that drives everything:

> The site sells **deeply** (87 commercial pages) but informs **shallowly**
> (3 informational pages). Buyers research with dozens of informational
> queries and the site captures none of that demand. **Every one of those
> queries is a doorway to an existing commercial page.**

Method:

1. **Crawl and inventory your own commercial pages.** Count by category:
   hubs, detail pages, sub-categories.
2. **Extract the entity set your products already cover** — every
   destination/feature/spec named anywhere in the catalogue.
3. **For each entity and product axis, enumerate the questions a buyer asks
   before buying:**
   - **Route/logistics** — "how do you get from A to B", "how long"
   - **Comparison** — "X vs Y, which should I choose"
   - **Sizing/duration** — "how many days do I need"
   - **Destination guides** — one per entity you sell into
   - **Buyer questions** — cost, safety, private vs group, with kids, packing
   - **Seasonal/experiential** — "best time to", "is it worth it"
4. **Discard any topic without an existing product page to link to.** This is
   the hard filter. Every proposed article names a **"Primary target linked"**
   column; a topic with no product is out of scope.

---

## 2. Prioritization — four scoring axes

| Column | Values | Meaning |
|---|---|---|
| **Intent** | TOFU / MOFU / BOFU | awareness / comparing / ready to buy |
| **CV** (Commercial Value) | High / Med / Low | closeness to a transaction |
| **BP** (Booking Potential) | ★ to ★★★★★ | likelihood a reader converts |
| **Primary target linked** | a specific URL | the money page this feeds |

**Build order: ship everything BOFU/MOFU with ★★★★+ first**, then descend.
TOFU destination guides come last despite often having the highest raw volume
— they convert worst and are easiest for competitors to match.

**The GEO-era fifth axis:** score topics by **how many AI questions they
answer**. "Is X safe?" answers 5; "7-day itinerary" answers 6; "first-timer
tips" answers 8. Use this when optimizing for AI citations rather than clicks.

---

## 3. Cluster map

Every commercial hub becomes a cluster centre:

```
CLUSTER {N} — {theme}
  Centre: {hub URL} + {single best-converting detail URL}
  Supporting articles: 3–10, each of which
     - links UP to the hub
     - links ACROSS to the single best-matching detail page
     - links SIDEWAYS to 2 sibling articles in the same cluster
  Hub/detail link DOWN to 2–3 of the articles
```

Cut clusters by **buyer journey stage or geography**, not by keyword-string
similarity.

---

## 4. Content brief — the 10-section template

```markdown
# Blog brief — "{Working title}"
> Drafted: {date} · Goal: {funnel stage, and where it funnels to}

## 1. Why this post first
   Strategic rationale — why this beats the other 49 candidates.
   Competitor benchmark: who ranks today, and the ONE angle that beats them.

## 2. Target keywords
   - Primary (1)
   - 5–8 secondary variants
   Mark as PLACEHOLDERS until confirmed with a volume tool.
   Note which secondaries map 1:1 onto an existing product page —
   "internal-link gold".

## 3. URL slug & file location
   Exact path. Which existing template it clones.

## 4. Recommended structure (H1 → H6 outline)
   - H1 (exact string)
   - Hero paragraph: ~80 words, answer in sentence 1, first-hand credentials,
     2 internal links
   - H2 — "The short answer" / TL;DR table   ← the must-skim element
   - H2 — Factor #1 … with per-bullet word counts (80–120 words)
   - H2 — "The N I recommend most" (~150 words each, opinionated)
   - H2 — Common mistakes (3 bullets; the E-E-A-T proof)
   - H2 — FAQ (6 questions, 40–60 words each, mirrored in schema)
   - H2 — CTA section
   EVERY structural element names its internal link target inline.

## 5. Internal link plan
   Target count up front (12–15). Grouped by cluster, then conversion pages,
   then sibling articles. Every link goes to a commercial or conversion page.

## 6. Schema markup
   Exact JSON-LD blocks (BlogPosting + FAQPage + BreadcrumbList) with
   required properties enumerated.

## 7. Meta tags
   Literal <title>, <meta description>. Chosen og:image + why.

## 8. Hero image
   Reuse an existing asset where possible; exact path + new alt text.

## 9. Word count target
   A range with justification for BOTH bounds.

## 10. After-publish checklist
   - [ ] add to index/teaser grid
   - [ ] add URL to sitemap.xml with lastmod
   - [ ] regenerate compressed siblings
   - [ ] request indexing in GSC
   - [ ] cross-link FROM the most related existing post
```

Two details worth keeping: **§4 carries its internal link target inline on
every heading**, so the writer can't forget them; **§10 always includes a
back-link from an existing page** — new content is never left to be discovered.

---

## 5. Linking back to money pages

1. Every article links **up** to its cluster hub **and** to the single
   best-matching detail page — in-body, descriptive anchor.
2. Every article cross-links to **2 sibling articles** in the same cluster.
3. The index links to all posts.
4. **Money-page → article is the direction everyone forgets.** Implement as an
   entity-triggered "related guides" block, max 3 per page.
5. Anchor variety: exact / partial / branded mix.
6. Deliverable is a **link matrix table** — one row per article, one cell
   listing every target URL, so it can be verified mechanically post-publish.
7. **Blog authority flows down, deliberately.** Editorial content carries
   trust signals Google weights differently; route that trust into commercial
   pages via in-body links rather than leaving it in the blog silo.
8. **Visible FAQ or no FAQ schema.** The most-repeated finding in the corpus:
   FAQ schema in `<head>` on 58 pages while the Q&A text was not visible in
   the body. Google suppresses the rich result in that case.

---

## 6. Site-wide editorial pass

### Method

1. Pass over every page; classify each issue into the taxonomy below.
2. Fix in place with an **exact-string-replace tool** — never a regex sweep
   over prose.
3. Record per-section highlights in the report.
4. Verify: `git diff --stat HEAD` → e.g. *"113 files changed, +3520 / −3370"*.
   **A roughly symmetric add/delete count is the signature of a text-only
   pass; a large net add means structure was touched.**

### Issue taxonomy — 13 categories

| # | Category | Look for |
|---|---|---|
| 1 | Spelling / typos | proper nouns misspelled inconsistently (same place spelled 3 ways) |
| 2 | Diacritics & proper nouns | missing accents, acronym casing (`Unesco` → `UNESCO`) |
| 3 | Compound-word breaks | `airconditioned` → air-conditioned; `twoday` → 2-day |
| 4 | US↔UK consistency | pick one variant, enforce site-wide |
| 5 | Capitalisation | proper-noun ranges capitalised; generic loanwords lowercased |
| 6 | Awkward phrasing | comma splices, missing articles, duplicated clauses |
| 7 | Truncated card blurbs | listing cards cut mid-word (`...explo`, `to K.`) |
| 8 | Empty / boilerplate metas | empty `og:description`; the same description copy-pasted |
| 9 | JSON-LD string values | `ImageObject.caption` duplicating the page `<title>` verbatim |
| 10 | Mojibake | `â€"`, `â€™`, `â‚¬`, stray control chars |
| 11 | Factual corrections | a wrong claim repeated across pages; internal contradictions ("20 years" vs `foundingDate: 2012`) |
| 12 | SEO meta improvements | titles → 55–60 chars; strip dated promos and `#1 TOP` stuffing |
| 13 | Card ↔ target mismatch | card title names a different product than the page it links to |

Produce a **cross-cutting normalisation table** (before→after for recurring
phrasings). It doubles as the spec for a future linter.

### Meta posture in the same pass

- **Titles** toward 55–60 chars, later refined to ≤68 with: *only trim titles
  that genuinely truncate; never touch the keyword head, only redundant
  `| Brand` suffixes.*
- **Meta descriptions** → 150–160 chars, primary keyword in the first 60.
  **Accepted overruns (166–170, keyword front-loaded) are left alone and
  documented** rather than degraded to hit a number.
- **OG/Twitter** realigned to the new meta.
- **`ImageObject` caption/description** rewritten to describe the image.
- **Keyword variations woven in only where they fit an existing sentence.**
  No stuffing, no structural changes.

---

## 7. DO-NOT-TOUCH list for bulk text edits

The single most transferable artifact in the corpus. **Never modify during an
editorial/SEO text pass:**

1. **URLs, `href`, `src`, filenames, folder slugs — even misspelled ones.**
   Slugs carry accumulated equity.
2. **Structural HTML** — tags, nesting, classes, IDs, `data-*`.
3. **JSON-LD structural keys** (`@type`, `@context`, property names). String
   *values* are editable; keys are not.
4. **Numbers with contractual meaning** — prices, durations, day counts,
   review counts, distances, stats.
5. **Itinerary/sequence content** — "Day N" headings, ordering, locations.
   Grammar inside a day's prose may be fixed; the day's *content* may not.
6. **`<header>`, topbar and `<footer>` on per-page files** — synced from a
   canonical source. Edit the source, run the sync.
7. **Redirect stubs and verification files** (`google*.html`,
   `BingSiteAuth.xml`, noindex stubs).
8. **Compressed `.br`/`.gz` siblings** — never hand-edit; regenerate.
9. **Already-clean pages** (legal, error pages) — don't churn them.
10. **Canonicals** — no canonical changes during a text pass.

**Additionally, on dynamic stacks:**

11. **Migrations, seeders, and factories** — a content pass never touches
    schema or fixtures.
12. **Route definitions and controller logic** — editing a route slug is a URL
    change (rule 1) wearing a different hat.
13. **Blade/template control flow** (`@foreach`, `@if`, `{{ }}` expressions).
    Edit the literal prose *between* directives, never the directives.
14. **Compiled/cached views** (`storage/framework/views`, `.next/`, build
    output) — regenerate them, never hand-edit.
15. **Translation files** for locales you are not passing over — a partial
    i18n edit is worse than none.

### Tooling rules

- Exact-string-match edits, UTF-8-safe. Not regex sweeps over prose.
- `io.open(..., encoding='utf-8')` in Python;
  `[System.IO.File]::WriteAllText(path, text, [System.Text.UTF8Encoding]::new($false))`
  in PowerShell. **Never `Get-Content`/`Set-Content`.**
- Verify zero mojibake introduced.
- Verify tag parity after any bulk edit.

### The four-number verification signature

```
git diff --stat HEAD    -> N files changed, +X / −Y   (X ≈ Y)
mojibake scan           -> none
tag imbalances          -> 0
URLs/hrefs changed      -> 0
```

---

## 8. Multi-agent execution pattern

Split work by **surface**, not by page range — that's what makes diffs
reviewable and verification greps meaningful:

- **Agent A — keyword assignment:** one primary + 4–5 secondaries per page,
  no two pages sharing a primary. This is the anti-cannibalization gate, done
  *before* any writing.
- **Agent B — on-page surfaces:** title, meta description, OG/Twitter, image
  alts, JSON-LD string values, H1, first paragraph. Fixed surface list =
  fixed diff shape.
- **Agent C — internal links:** 3–5 per page, into existing prose.
- **Agent D — schema:** JSON-LD appended before `</head>`, all blocks
  validated as parseable.

---

## 9. Checklist

- [ ] Topics selected inventory-first; every topic names its money page
- [ ] Topics without a linkable product page discarded
- [ ] Four-axis prioritization applied; BOFU/MOFU ★★★★+ shipped first
- [ ] Cluster map written, centres and spokes named
- [ ] Every brief has all 10 sections, with link targets inline in the outline
- [ ] Every article links up, across, and sideways
- [ ] Money-page → article direction implemented
- [ ] Visible FAQ present wherever FAQ schema exists
- [ ] Editorial pass verified by the four-number signature
- [ ] DO-NOT-TOUCH list respected; zero URL/slug/canonical changes
- [ ] After-publish checklist completed per post
