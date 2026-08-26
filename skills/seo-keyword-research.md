---
name: seo-keyword-research
description: >
  Per-page keyword research, targeting and retargeting at site scale. Covers
  the pilot→batch→full-site pass structure, SERP-composition scoring, the
  one-keyword-one-page ownership map, title/meta rules with rendered-length
  limits, and the 6 metadata bug classes that keyword work reliably uncovers.
  Never fabricates volume data.
---

# SEO Keyword Research Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

Built from a 114-page site researched across four passes. The method is
**per-page and evidence-logged**. Bulk pattern-matching is explicitly
forbidden — it is what this methodology exists to prevent.

---

## 0. Pass structure — and why the pilot exists to be wrong

| Pass | Entry condition | Allowed output | Forbidden |
|---|---|---|---|
| **Pilot** (5–6 pages) | Start of project | Metadata edits + a validated *or invalidated* pattern | Applying the pattern site-wide |
| **Batch** (6–15 pages) | Pilot pattern validated | Same edits, per-page verified | Pattern-matched find/replace |
| **Full-site** | Batch confirms it generalizes | Per-page verdicts + edits | Skipping "obviously identical" pages |
| **Verification** | After any pass claiming N changes | Corrections, false-claim list | New research |

> The reference pilot produced the rule "private + from-city". Batch 2
> tested it on six untouched pages and found the bare form was **just as
> saturated** as the term it replaced. The rule was corrected to require a
> downstream route/destination qualifier. Applying the pilot's rule
> immediately would have retargeted ~70 pages onto a term with the same
> competitive problem.

**Rule: never generalize a pilot pattern beyond the next batch without
re-testing it on pages the pilot did not touch.**

---

## 1. Per-page workflow

```
1. Read the page's actual on-page state from source
   → title, meta description, H1, H2s, og:*, twitter:*, canonical, all JSON-LD
2. Read the page's actual CONTENT (body, itinerary, feature list)
3. Generate candidates from real attributes
   → {modifier} × {origin} × {entity} × {size/duration} × {category noun}
   → include the page's current phrasing as one candidate
4. Pull a live SERP for each serious candidate
5. Classify SERP COMPOSITION  ← the real scoring input
6. Honesty check against step 2
7. Ownership check against the map
8. Verdict: KEEP / MINOR UPDATE / RETARGET + confidence + evidence label
9. Apply — FULL element sync (§4), never title-only
10. Validate
```

Steps 5 and 6 produce almost every real finding. **Step 5 decides
winnability; step 6 decides legitimacy. Volume decides neither alone.**

### Step 5 — what to record from the SERP

- Count of aggregator/marketplace results
- Count of independent competitors of comparable size
- Count of big-publisher/brand results
- **The exact literal wording competitors use in their titles**
- The page TYPE that ranks (product / listicle / guide / forum)

That fourth item is the highest-yield field in the whole method. Repeatedly
the winning change was not a new keyword but adopting the **literal noun the
ranking set uses** — a proper entity name over a generic one, "walking tour"
over "tour", "ascent" over "trek".

> **Entity corrections beat keyword swaps.**

---

## 2. Data sources — never fabricate

Three sources, ranked, never silently blended:

1. **Live SERP inspection** — always available. Primary evidence for
   winnability and competitor phrasing.
2. **Paid volume/KD data** (Semrush, Ahrefs…) — for prioritization and
   tie-breaks only. Never the sole justification.
3. **The page's own content** — source of truth for what may honestly be claimed.

> No volume or KD figure may be estimated or fabricated. Where absent,
> label it unavailable.

Valid evidence labels: `SERP-supported opportunity` ·
`Inferred from related-query evidence` · `Volume unavailable` ·
`SEMRUSH 320/KD41`.

**`NEEDS PAID VOLUME DATA` and `RESEARCH INCONCLUSIVE` are valid terminal
verdicts.** One reference page sat inconclusive across three passes and was
correctly left alone each time rather than retargeted on a guess.

Also record which connectors you **checked and found unavailable** — it makes
the evidence base auditable.

---

## 3. Evidence log — one block per page

```
## {page-slug}
- Title: "{current}"  ·  H1: "{current}"
- Candidates: {a} · {b} · {c}
- Query searched: "{query}" ({date})
- SERP: {competitor} ({type}, "{exact title wording}"), ...
- Observations: {who wins, dominant phrasing, intent}
- Label: {evidence label}
- Verdict: {KEEP|MINOR UPDATE|RETARGET} — {one-line reason}
```

`Query searched … (date)` makes the finding falsifiable later — SERPs move,
and an undated SERP claim is unverifiable.

Machine-readable companion, one row per page:

```
URL, Page Type, Primary Keyword, Secondary Keywords, Intent, Volume, KD,
Evidence Source, Current Title, New Title, Current H1, New H1,
Decision, Confidence, Cannibalization Owner
```

`Evidence Source` and `Cannibalization Owner` are what make it auditable.

---

## 4. Decision cascade — first rule to fire wins

There is no numeric score. Apply in order:

1. **Honesty veto.** Content doesn't support the term → reject. No volume
   overrides this.
2. **Winnability veto.** SERP structurally owned by a class you can't join
   (marketplaces, official venue sites, national publishers, government) →
   reject regardless of volume. Keep the page for internal linking and direct
   capture; record "do not expect head-term rank".
3. **Intent match beats volume** when the gap is small. A commercial page
   takes the commercial term over a higher-volume informational one.
4. **Ownership veto.** Another page owns it → this page can't have it.
5. **Volume/KD as tie-break only**, among survivors of 1–4.
6. **Confidence H/M/L** on every verdict. Low confidence → `KEEP` + flag,
   never `RETARGET`.

Effort priority: **hubs first** (they set boundaries children inherit), then
detail pages, then activity/blog/static.

**Legal and utility pages have no keyword strategy.** No commercial query
exists for a privacy policy. Carve them out rather than forcing them through.

---

## 5. The ownership map — one keyword, exactly one page

The single most reusable artifact.

| Page | Primary owner of |
|---|---|
| `/` | {brand/head term} |
| `/category-a/` | {category term} |
| `/category-a/item-1/` | {exact-match long-tail} |

Mechanics:

- **Written before edits, enforced during, re-verified after.**
- Every per-page plan carries a `Cannibalization check:` line naming terms
  the page must **not** target and who owns them.
- **Protected terms** — pages settled in an earlier pass are marked
  `PROTECTED`; every later workstream receives the list up front.
- **Hub/child boundary:** the hub owns the **category** term; children own
  **exact-match specific** terms. The hub must stay generic.
- **Pre-implementation uniqueness check** across all primaries, before any
  edit. On the reference project this caught a genuine conflict and forced a
  swap before code was touched — cheap, catches the expensive mistake.
- **Post-implementation single-owner table**, owner count must equal 1:

```
| Keyword | Owners |
| term a  | 1 ✓    |
| term b  | 1 ✓ (was 2 — resolved) |
```

- **Enforcement is bidirectional** — actively remove owned terms from
  non-owner pages where an earlier pass borrowed them into alts, captions,
  and descriptions.

---

## 6. Title and meta rules

### Hard limits

| Element | Target |
|---|---|
| `<title>` | **50–60 chars** (accepted band 44–65) |
| `meta description` | **145–160 chars** |
| H1 | No char limit — must fit the existing hero/section width |

> **Count RENDERED length, not raw HTML bytes.** Source strings run longer
> where `&amp;` and similar entities appear; the SERP truncates rendered
> text. A checker counting raw source characters produces false
> "over-limit" findings on any title containing `&`.

### Title formula

```
{Qualifier} {Specific-Entity/Route} {relationship} {Origin} | {supporting scope}
```

- **Lead with the exact phrase in the word order the ranking set uses.**
  Don't split a phrase competitors keep contiguous.
- **Drop `| Brand` on non-brand pages** — frees character budget for keyword.
  Keep it on About/Contact/legal, where it's correct.
- **Duration/size first** when that's the differentiator between siblings.
- **Name the specific differentiating entity** when siblings collide.
- Match the singular/plural the data actually supports.

### A title is BAD if…

| Signal | Why |
|---|---|
| Leads with a brand/category keyword unrelated to the page's topic | Destroys topical clarity |
| Contains a claim the content doesn't support | Intent mismatch → bounce |
| Repeats the head term 2–3× in the meta description | Stuffing; reads as machine copy |
| Near-identical to a sibling, separated only by generic wording | Real cannibalization |
| Uses in-house vocabulary no competitor uses | Wrong-language targeting |
| Describes the page relative to *other pages on the same site* | Internal notes leaked publicly |
| Title says one thing, H1 another | Inconsistency (see bug 3) |
| Generic where the SERP rewards specific | Unwinnable |

A title is **good** when it is the literal phrase the winning set uses, honest
to the content, unique in title and H1 site-wide, 50–60 rendered chars, and
its page is the sole registered owner of its primary term.

### Nine transformation patterns

1. **De-contaminate** — strip a boilerplate category prefix pasted onto every
   page: `{Category} Tour Add-On: {Real Topic}` → `{Real Topic}`
2. **Add the differentiating qualifier** — `{Duration} {Category} Tour from
   {Origin}` → `{Duration} Private {Entity} Tour from {Origin} to {Dest}`
3. **Honesty retitle** — remove the unsupported category word
4. **Promote the H1's wording into the title** where they mismatch and the
   SERP supports the H1 side. Fastest, most defensible fix — it resolves an
   inconsistency the site already had. Costs nothing to check for.
5. **Entity correction** — generic noun → the proper entity name the ranking
   set uses. Highest-yield, lowest-risk.
6. **Reorder to SERP-dominant word order** — same words, competitor's sequence
7. **De-stuff the meta** — one clean sentence describing the actual content
8. **Genericize a hub description** that enumerates its children's exact terms
9. **Strip self-referential cross-sell language**

---

## 7. The 6 metadata bug classes

Found *while* doing keyword work; none are keyword problems. All are
mechanically checkable on any site.

### Bug 1 — Copy-paste contamination across a page family
Template siblings carry another group's attribute values in `og:*` and schema
— wrong origin, category, or subject. Often with grammatical breakage from a
bad merge (`"X tour from Y & Y, 2 Days"`).

```bash
# for a page under /origin-b/, flag any other origin named in its meta/schema
grep -l 'og:.*{other-origin}' origin-b/*/index.html
```

Text half is fixable in a metadata pass; a **wrong image** is an asset gap —
report it separately rather than claiming a full fix.

### Bug 2 — Template-default social tags
`og:title`/`og:description` silently defaulting to a site-wide boilerplate.
Invisible in normal QA because `<title>` is correct — only shows when shared.

```bash
grep -rho 'property="og:title" content="[^"]*"' --include=*.html . | sort | uniq -d
```

Assert `title == og:title == twitter:title` after entity-decoding **and after
normalizing deliberate site conventions** (a site legitimately dropping
`| Brand` from social tags — the normalizer must know, or it emits false
positives).

### Bug 3 — Internal/editorial language leaked into public copy
Meta descriptions describing the page relative to other pages on the same
site: *"the reverse of X"*, *"a 4-day alternative to our other tour"*,
*"pairs with the Y tour"*. Diagnostic: **no real competitor phrases their own
product this way.**

Grep meta descriptions for: `alternative to`, `the reverse of`, `pairs with`,
`similar to our`, `see also`, `our other`, `as opposed to our`.

### Bug 4 — Content/intent mismatch (the honesty bug)
A page titled with a category attribute its content lacks. Costs double:
wrong traffic bounces, and the correct query is never targeted.

For each category keyword in a title/H1, assert supporting evidence exists in
the body. Where the competitor set for that route never uses the word, that's
corroborating evidence.

> **Honesty debt:** a first pass fixed these in **titles only and never
> reached the H1s** — 7 pages still carried the false claim after being logged
> as "fixed". Any fix of this class must cover the full element set.

### Bug 5 — Social-tag truncation / title mismatch
`og:title` holding a truncated stub of the real title, or leading with
different framing. Flag any page where `og:title` is a strict substring of
`<title>`, is under ~60% of its length, or begins with a different phrase.

### Bug 6 — Stale schema fields after a metadata edit
**The highest-yield mechanical check in the corpus.** `<title>`, `og:title`
and `twitter:title` all correctly updated while a separate JSON-LD field on
the same page (`{Type}.name`, `.description`, `BreadcrumbList.ListItem.name`,
`ImageObject.caption`) silently keeps the pre-edit value. Found on **11 pages**
by verification, missed entirely by the original pass; a later pass found
**13 more** with a stale breadcrumb name pointing at a hub's old title.

```
for each edited page:
  extract title, og:title, twitter:title,
          every JSON-LD *.name / *.description / *.caption / *.headline,
          BreadcrumbList leaf name
  assert no field still contains any OLD string being replaced
  assert JSON-LD parses
```

Plus a site-wide **stale-reference sweep** — a renamed hub's old name persists
in *other* pages' breadcrumbs:

```bash
grep -rF "{old title string}" --include=*.html .   # every hit must be intentional
```

**Deliberate non-edits:** breadcrumb `ListItem.name` values serving as stable
navigational labels stay unchanged where they are not stale. And do **not**
rewrite a generic `Organization.description` to force an exact-match keyword —
that flag is an audit-checker artifact, not a real defect.

---

## 8. Post-pass validation checklist

```
Structural
[ ] exactly one <h1> per page
[ ] canonical present on every page
[ ] no accidental noindex introduced

Consistency
[ ] title == og:title == twitter:title (decoded, conventions normalized)
[ ] no JSON-LD field still contains any replaced OLD string
[ ] breadcrumb leaf names synced where stale, untouched where not
[ ] H1 carries the same claim as the title (no honesty debt)

Uniqueness
[ ] 0 duplicate titles / meta descriptions / H1s site-wide
[ ] each owned keyword: exactly 1 owner in title+H1
[ ] protected pages untouched (verified via git status)

Length
[ ] every changed title and description within band, measured RENDERED

Integrity
[ ] every JSON-LD block re-parses
[ ] stale-reference sweep clean
[ ] no mojibake introduced
[ ] derived artifacts regenerated (minified mirrors, .br/.gz siblings)
[ ] no URLs, slugs, canonicals, CSS, JS, nav, footer modified

Scope
[ ] git status shows ONLY intended files
[ ] every changed file corresponds to a documented decision
```

> If a site serves derived artifacts (pre-compressed siblings, minified
> bundles), **a source-only edit does not ship.** The reference project caught
> stale compressed siblings dated before the edits — they would have served
> the old markup to real visitors.

---

## 9. The eleven transferable rules

1. Pilot on 5–6 pages; **re-test the pattern on a second batch before generalizing.**
2. Every page gets an individual SERP check. Bulk pattern-matching is forbidden.
3. **Never fabricate volume.** "No data" is a valid terminal verdict.
4. SERP *composition* beats volume for winnability. Volume is a tie-break.
5. **Honesty veto outranks everything.**
6. **Entity corrections beat keyword swaps.**
7. One keyword, one page. Ownership map with protected terms.
8. Cannibalization severity is set by **element** (title/H1 real; body usually fine).
9. **Exhaust differentiation before proposing consolidation.**
10. Fix the **full element set** or it isn't fixed.
11. **Verify against artifacts, not prose.**

See `seo-cannibalization.md` for the detection methods and resolution tree,
and `seo-verification.md` for the verification pass in full.
