---
name: seo-cannibalization
description: >
  Detect and resolve keyword cannibalization, thin content, and near-duplicate
  pages. Four independent detectors (string collision, element-classified
  occurrence census, Jaccard body similarity, SERP overlap), a resolution
  decision tree that exhausts differentiation before proposing a merge, and
  boilerplate-subtracted thin-content thresholds.
---

# SEO Cannibalization & Duplicate Content Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

Two rules frame everything here:

> **Cannibalization severity is determined by ELEMENT, not by raw occurrence
> count.** Title and H1 collisions are real. Body and meta-keyword mentions
> are usually natural qualifiers and should be preserved.

> **Exhaust differentiation before proposing consolidation.** Merges and
> redirects are owner-approved, data-gated escalations — never an audit
> auto-fix.

---

## 1. Four detectors — run all of them

Each catches something the others miss. Running only one produces both false
positives and false negatives.

### (a) Exact-string collision scan

Before applying any new title, grep the whole site:

```bash
grep -rF "{proposed title}" --include=*.html .

# whole-site duplicates
grep -rho '<title>[^<]*</title>'                      --include=*.html . | sort | uniq -d
grep -rho '<meta name="description" content="[^"]*"'  --include=*.html . | sort | uniq -d
grep -rhoP '(?<=<h1[^>]*>)[^<]*'                      --include=*.html . | sort | uniq -d
```

Enforce zero duplicates in all three, re-checked after every edit.

### (b) Element-classified occurrence census — the important one

For each owned keyword, count occurrences **and classify where each sits**:

```bash
grep -ril "{owned keyword}" --include=*.html . | wc -l
```

Then classify every hit by element: **title / H1 / heading / meta description
/ body / meta-keywords**.

> On the reference project one owned phrase appeared on **15 pages** — but
> classification showed **0 in title and 0 in H1**, so the hub's ownership was
> intact and no edit was needed. Without element classification, the raw count
> of 15 would have triggered 14 unnecessary edits.

### (c) Jaccard body similarity

```python
import re, itertools, pathlib

def body_tokens(path):
    t = pathlib.Path(path).read_text(encoding='utf-8', errors='replace')
    t = re.sub(r'(?s)<(script|style)[^>]*>.*?</\1>', ' ', t)
    t = re.sub(r'(?s)<nav.*?</nav>|<footer.*?</footer>|<header.*?</header>', ' ', t)
    t = re.sub(r'<[^>]+>', ' ', t)
    return set(re.sub(r'[^a-z0-9 ]', ' ', t.lower()).split())

def jaccard(a, b):
    return len(a & b) / len(a | b) if (a | b) else 0.0

pages = {p: body_tokens(p) for p in pathlib.Path('.').rglob('*.html')}
for (pa, ta), (pb, tb) in itertools.combinations(pages.items(), 2):
    s = jaccard(ta, tb)
    if s >= 0.45:
        print(f'{s:.1%}\t{pa}\t{pb}')
```

| Jaccard | Risk |
|---|---|
| ≥ 50% | HIGH — act now |
| 45–50% | MEDIUM — next sprint |
| 35–45% | LOW — fine if the pages are genuinely different topics |
| < 35% | fine |

> **Critical caveat:** the two worst-cannibalizing pages in the reference
> corpus scored only **31.7%** body similarity — LOW — while having
> near-identical title, H1 and meta. *Low body Jaccard masks critical
> title/H1/meta pollution.* **Body similarity alone is not a cannibalization
> detector.** Always pair it with (a) and (b).

### (d) SERP overlap — the arbiter

Pull the SERP for each page's target term and compare result sets. **Zero or
low overlap ⇒ Google treats the intents as distinct ⇒ not cannibalization**,
however similar the pages look.

Supporting evidence: **do real competitors maintain separate dedicated pages
for this same distinction?** If the entire competitive set runs separate URLs
per duration/variant, and third-party "X vs Y" comparison content exists, the
distinction is real market segmentation, not duplication.

### (e) Structural comparison — for hub pairs

Compare the outbound link sets of two suspected duplicate hubs. Two hubs
linking to an identical set of children is decisive evidence, independent of
any text metric.

---

## 2. The taxonomy — four patterns, four fixes

**A. Category-keyword pollution (site-wide).** One brand/category phrase
pasted into the title and H1 of every page regardless of topic — 33 pages on
the reference site. Google can't decide which to rank, so it ranks none well.
*Fix:* re-topic each page's title and H1 to its actual subject; leave the
phrase to its one legitimate owner.

**B. Hub over-reaching onto its children.** The hub's meta/OG description
enumerates all its children's exact destination names — direct duplicate
targeting against each child's exact-match phrase. *Fix:* genericize the hub
to category-level language.

> Deliberate non-fix: the same duplication in the `meta keywords` tag was
> **left alone** — it has carried zero ranking weight since 2009, so editing
> it doesn't reduce risk. **Don't generate work with no mechanism of effect.**

**C. Sibling collision on generic wording.** Two long-form siblings whose
titles differ only in generic word order. *Fix:* retitle each to name its
**real differentiator** — the specific region or stop each actually adds.

**D. Duplicate hubs.** Two hubs, same children, same intent signals. See the
tree below.

---

## 3. Resolution decision tree

```
Suspected cannibalization between A and B
│
├─ Same PAGE TYPE and same INTENT?
│   NO ──→ NOT cannibalization. Document the boundary, move on.
│          (commercial page vs informational guide on the same subject;
│           category hub vs exact-match child; day-trip vs overnight variant)
│   YES ↓
│
├─ Do their target SERPs OVERLAP?
│   NO ──→ NOT cannibalization. Record the evidence and clear it explicitly.
│   YES ↓
│
├─ Does the competitive set maintain SEPARATE pages for this distinction?
│   YES ──→ DIFFERENTIATE, do not merge.
│           Legitimate segmentation. Sharpen each title to its differentiator.
│   NO ↓
│
├─ Is the overlap only ONE page's METADATA reaching into the other's
│  territory (hub naming children, borrowed term in alt/caption)?
│   YES ──→ GENERICIZE / STRIP the offending metadata on the non-owner.
│           Cheap, safe, unilateral. No architecture change.
│   NO ↓
│
├─ Can title/H1 differentiation alone separate the two intents?
│   YES ──→ DIFFERENTIATE BY TITLE. Each gets a distinct head term;
│           the weaker page CEDES the contested term to the stronger.
│   NO ↓
│
└─ CONSOLIDATE or REDIRECT
    ⚠ ESCALATE — never unilateral.
    Requires: owner approval + real GSC data showing the pages actually
    split impressions for the same query.
```

### The escalation rule matters most

On the reference project, consolidation was flagged in July, re-examined in
August, and **still deliberately not executed** — with the reasoning stated
each time:

> Which page is canonical is a **site-architecture decision** that a
> keyword-metadata pass should not make unilaterally.

Re-open condition, made explicit and data-gated:

> Revisit consolidation ONLY if GSC shows them splitting impressions for the
> same query; any merge/redirect requires owner approval.

The August pass then found a cheaper resolution: the three hubs mapped to
three genuinely distinct intents, so **title differentiation was sufficient
and consolidation became unnecessary.**

**A cannibalization check that concludes "no merge" is a valid, valuable
output.** Record the verdict and the reasoning. Never merge on string
similarity alone.

---

## 4. Thin content

### Detection

```bash
for f in $(find . -name '*.html' -not -path './vendor/*'); do
  wc=$(sed -e 's/<script[^>]*>.*<\/script>//g' -e 's/<style[^>]*>.*<\/style>//g' \
           -e 's/<[^>]*>/ /g' "$f" | tr -s '[:space:]' '\n' | grep -c .)
  echo "$wc $f"
done | sort -rn
```

| Words | Flag |
|---|---|
| < 600 | THIN |
| 600–800 | LOW |
| 800–1000 | OK |
| 1000+ | GOOD |

### The critical refinement — raw count is a trap

**Nav + footer contribute ~400–500 shared words to every page.** The
reference audit concluded "no genuinely thin pages" (minimum 896 words) — and
that verdict was wrong at raw-count level.

```
unique_words ≈ raw_words − shared_boilerplate_words
```

Measure the boilerplate empirically: word-count the nav+footer partial, or
compute shared-token overlap between the two most different pages. Here it
was ~450.

Re-scored with subtraction, **7 hub pages sat at ~650–700 unique words** —
MEDIUM risk, completely invisible in the raw table.

**Target: 800+ words of *unique* content per hub page, excluding nav/footer.**

### The combined signal is what matters

> The problem is **thin + similar**, not thin alone. Combined with HIGH
> similarity scores, these pages look thin AND duplicative — the worst
> combination for indexing.

Score on both axes and act on the intersection:
`unique_words < 800` **AND** `max_pairwise_jaccard > 50%`.

---

## 5. Duplicate root causes and remediation

Three archetypes:

1. **Templated hub pages** — same pitch paragraph, same card structure, only
   the location swapped. (55–58% similarity.)
2. **Boilerplate-dominated detail cluster** — shared intro, CTA and FAQ; the
   only unique content is the itinerary table. (51–58%.)
3. **Same product, different departure** — identical body, only logistics
   differ. Google treats these as duplicates when the differentiator is just
   "drive from X instead of Y". (46–50%.)

### Remediation patterns

**A — Unique intro pass.** Every hub gets a unique first paragraph, minimum
**100 words** of unique prose referencing the location's actual geography and
travel context, linking to 1–2 related hubs or posts.

> **Uniqueness test: if the intro could appear word-for-word on a different
> hub page without modification, rewrite it.**

**B — Context-specific section on detail pages.** Add **150–250 words** unique
to the departure/context: what makes it distinctive as a starting point,
distance, route highlights, what the traveller sees first. Not copy-pasteable
to a sibling.

```python
def insert_before_cta(html, section_html):
    marker = '\n    <section class="cta-band">'
    idx = html.find(marker)
    if idx == -1:
        idx = html.rfind('</main>')
        if idx == -1: return None
    return html[:idx] + '\n' + section_html + html[idx:]
```

The differentiator must be **tangible**, not just logistics: "via the kasbah
route on the way" beats "shorter drive" — a different experience, not a
different drive time.

**C — Demote or cut boilerplate.** Move shared intro/CTA into a `<details>`
accordion or cut it. Reduces the shared-token denominator directly.

**D — Add genuinely useful unique blocks.** Each thin commercial page gains:
a comparison table, a best-season/who-it's-for note, a 6–8 item FAQ matching
real queries (+ matching FAQPage schema and **visible** markup), and internal
links to relevant guides.

**E — Differentiate the metadata layer separately.** Rewrite title/H1/meta so
each expresses a distinct intent, even when body similarity is acceptable.

**F — Do not delete.** No page needs deletion for thin content alone. Fix
similarity first; effective word count rises as a side effect. **Never rename
URL slugs** — they carry equity.

---

## 6. Checklist

- [ ] All four detectors run (string collision, element census, Jaccard, SERP overlap)
- [ ] Occurrence counts classified by element before any edit
- [ ] Body-similarity results cross-checked against title/H1/meta similarity
- [ ] Every suspected pair taken through the decision tree, verdict recorded
- [ ] "No merge" verdicts documented with reasoning
- [ ] No consolidation or redirect executed without owner approval + GSC data
- [ ] Thin-content scored on **boilerplate-subtracted** unique words
- [ ] Pages scored on both axes; intersection prioritized
- [ ] Zero duplicate titles / descriptions / H1s after the pass
- [ ] No URL slugs renamed, no pages deleted
