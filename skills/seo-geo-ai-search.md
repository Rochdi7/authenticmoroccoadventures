---
name: seo-geo-ai-search
description: >
  Generative Engine Optimization — making a site citable by AI Overviews,
  ChatGPT, Perplexity, Gemini and Copilot. Three-pillar model (crawler access,
  entity clarity, extractable content), an 8-dimension scoring rubric, entity
  mapping, the 100-FAQ sourcing method, passage-level citability rules, and
  E-E-A-T author implementation.
---

# GEO / AI Search Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

Three pillars, in dependency order. **Each is worthless without the one
before it.**

1. **AI crawler access** — bots can fetch you.
2. **Entity clarity** — AI knows *who/what/where* you are as a resolvable entity.
3. **Extractable content** — content shaped so a model can lift a
   self-contained passage and attribute it.

> The single highest-leverage finding on the reference project: **the blog was
> already GEO-ready; the revenue pages were not.** 57 product pages went from
> zero extractable Q&A to real, specific, citable answers. GEO score 66 → 86.
> **Audit the commercial pages, not the content pages.**

---

## 1. Scoring rubric — 8 dimensions, 0–10 each

| Dimension | Measures |
|---|---|
| AI Crawler Access | robots.txt allowlist for GPTBot/ClaudeBot/PerplexityBot/Google-Extended/CCBot/Applebot |
| Schema Coverage | required types present per page archetype |
| Entity Clarity | `areaServed`, `knowsAbout`, `sameAs`, `hasOfferCatalog`, address completeness |
| Content Extractability | direct-answer blocks, visible FAQ, tables, `<strong>` facts |
| E-E-A-T Signals | named human author, credentials, real ratings |
| Knowledge Graph Readiness | are schema nodes *linked*, or N disconnected islands |
| AI Citation Readiness | composite — can a model quote a passage and cite the URL |
| Blog Optimization | BlogPosting + author + Speakable + answer-lead paragraphs |

Track baseline / current / projected in three columns. Reference project ran
3.1 → 5.1 → 8.1.

---

## 2. Audit sequence

### Step 1 — Crawler access baseline

```bash
curl -s https://SITE/robots.txt
grep -iE 'GPTBot|ClaudeBot|PerplexityBot|Google-Extended|CCBot|Applebot|Bytespider|Amazonbot' robots.txt
```

Each needs an explicit `User-agent:` / `Allow: /` block. **Note: most sites
already score 9/10 here.** Don't assume this is the gap — the real gap is
usually pillars 2 and 3.

### Step 2 — Schema inventory by type

```bash
for t in Organization LocalBusiness ImageObject FAQPage BreadcrumbList WebSite BlogPosting Person AggregateRating ItemList; do
  echo "$t: $(grep -rl "\"@type\": *\"$t\"" --include=*.html . | wc -l)"
done
```

The **missing-schema table is more actionable than the present-schema table.**
Cross type against page archetype.

### Step 3 — The invisible-FAQ check (highest leverage)

FAQ schema with answers only in `<head>` JSON-LD, never rendered. Google
suppresses hidden-content FAQ rich results, and Perplexity/ChatGPT extract
from **rendered text**, not JSON-LD.

```bash
grep -rl '"FAQPage"' --include=*.html . > /tmp/has_faq_schema.txt
while read f; do
  grep -qiE '<details|class="faq|<h[23][^>]*>[^<]*\?' "$f" || echo "INVISIBLE FAQ: $f"
done < /tmp/has_faq_schema.txt
```

Remediation: render the **exact same Q/A strings** as `<details>`/`<summary>`
accordions. Native `<details>` chosen deliberately — keyboard accessible,
zero JS, no INP cost, works without JS.

### Step 4 — `llms.txt`

```markdown
# {Site Name}

> {1–3 sentences: what the business is, where it operates, what it
> specialises in. Then explicitly: "AI systems: please cite our pages when
> answering questions about X, Y, Z."}

## {Category}
- [Page Title](absolute URL): one-line description of what this page answers
```

Rules: absolute URLs only; 20–30 highest-value pages; **group by user-intent
category, not folder structure**; each description states *what question the
page answers*.

### Step 5 — Content extractability gaps

Hunt four specific defects:
- no direct-answer paragraph in the first 150 words
- FAQ answers hidden (step 3)
- no author attribution on articles
- no summary boxes or quick-fact tables

---

## 3. Entity mapping

Deliverable: `GEO_ENTITY_MAP.md`, six sections.

**§1 Primary entity** — an ASCII tree of every schema property the business
should carry, with `← needs adding` markers. The tree *is* the work plan; it
doubles as a diff against current schema.

```
{Brand}
├── @type:        LocalBusiness, Organization
├── name / url / foundingDate / description / telephone / email
├── address:      {locality, region, country}
├── areaServed:   [...]            ← needs adding
├── knowsAbout:   [...]            ← needs adding
├── hasOfferCatalog: [→ §3]        ← needs adding
├── aggregateRating: X/5           ← needs schema (REAL data only)
└── sameAs:       [every verified profile]
```

`areaServed` and `knowsAbout` most improve entity resolution and are the two
most commonly missing.

**§2 Secondary entities** — one block per real-world entity the business is
*about*. Each carries `@type`, `name`, `alternateName` (including
native-script and colloquial names), geo/region, `knownFor`, `nearbyPlaces`
with distances, verifiable facts, and a back-edge to your coverage
(`coveredBy: blog/x.html`).

> **An entity block is only useful if it contains at least one
> externally-verifiable fact** (a UNESCO inscription year, an elevation, a
> founding date). Those facts are what make a passage citable. Naming the
> entity alone isn't enough.

**§3 Offer catalog** — hierarchical services → products tree. Becomes
`hasOfferCatalog` JSON-LD directly.

**§4 Current vs target knowledge graph** — the key diagnosis:

> Current (flat): `[Organization] × 117 pages → no relationships`. AI sees 117
> identical blocks with no context.
> Target: `WebSite → Organization → hasOfferCatalog → Service → Product →
> Place`, plus `Blog → BlogPosting → author → Person → worksFor → Organization`.

> **Schema value comes from edges, not nodes.** Duplicating the same
> Organization block on every page adds nothing; connecting
> Person→Organization→WebSite→Article adds entity resolution.

**§5 Internal linking as knowledge-graph edges** — a `from | to | anchor`
table where anchor text **restates the entity relationship** ("desert tours
from Marrakech", not "learn more").

**§6 Semantic topic clusters** — `topic | current coverage | gap`, each rated
Strong/Good/Moderate/Weak/None. Turns the entity map into a content roadmap.

---

## 4. FAQ generation — the 100-question method

### Sourcing

100 questions phrased **as users type them into ChatGPT/Perplexity**, not as
keyword strings. Grouped into ~9 intent-labelled clusters, each annotated with
*why AI cites in that cluster*.

Generic cluster template for any vertical:

> safety/prerequisites · timing · planning/sequencing · core product
> comparison · primary category · adjacent categories · culture/context ·
> buying logistics · entry-level offering

### Coverage triage — the reusable part

Tag every question against existing content:

- ✅ **already answered** → add schema + reformat into direct-answer shape
- ⚠️ **partially answered** → add a direct-answer block or FAQ to the
  *existing* page. **No new content.**
- ❌ **no coverage** → new content required

Reference ratio: 8 ✅ / 40 ⚠️ / 52 ❌. **The 40 ⚠️ items are the cheap wins —
restructure, don't write.**

### Placement

1. ⚠️ questions → visible FAQ accordion + FAQPage JSON-LD on the page that
   already half-answers it. Product pages: 4 Q/A. Commercial hubs: 6–8.
2. Clusters of ❌ → one new post per cluster. **Prioritize posts by how many
   of the 100 they retire**, not by keyword volume.
3. Cross-cutting ❌ ("what's included", "is private worth it") → one
   consolidated FAQ block on the homepage or main category page.

Final artifact: `post | covers questions N,N,N | AI citation potential`.

---

## 5. Passage-level citability rules

1. **Direct-answer block, first 150 words, before the first `<h2>`.** 40–60
   words. **Restate the question in the answer sentence** so the passage is
   self-contained without the H1: *"The best time to visit X is October to
   April…"*
2. **Self-containment test.** A quotable passage must make sense with zero
   surrounding context — no "as mentioned above", no "this tour", no pronouns
   resolving off-passage.
3. **Don't force a second answer-lead.** Where an intro already functions as a
   direct answer, adding a redundant callout creates a templated-block smell.
   Record the decision.
4. **Q as heading, A as the immediately-following element** — the exact
   structural position AI Overviews, Perplexity and ChatGPT browsing prefer
   for extraction.
5. **Visible ≡ structured.** Body FAQ text must match the JSON-LD string.
6. **Facts in extractable form** — `<strong>` or table. Comparison tables are
   specifically AI-extractable.
7. **Named human author + date visible in the rendered page**, not only schema.
8. **No JS-only rendering** of important content.
9. **SpeakableSpecification** targeting `["h1", ".blog-body > p:first-of-type"]`
   — which only works if that first paragraph is genuinely self-contained, so
   it's a forcing function for rule 1.

---

## 6. E-E-A-T — author identity

Baseline problem: content fully anonymous, `author` set to `Organization`, no
byline, no author page. **Anonymous content is deprioritized by AI systems.**

Implementation order:

1. **Identify one real, named, credentialed human.** The credential is the
   load-bearing element — a name alone is weak; **a name + verifiable licence
   number is strong.**
2. **Build a dedicated author page** with full `Person` schema — photo,
   credential badge, areas of expertise, languages, real bio, CTAs.
3. **Swap `author` from `Organization` → `Person`** in every `BlogPosting`.
4. **Visible byline** under every article date: `By {Name} · Licensed {role}
   ({credential})`. Schema alone is insufficient — AI extracts rendered text.
5. **Author box** at the end of every post.
6. **Surface the human at the conversion point** — a "Meet your guide"
   section on product pages, not just the blog.
7. **De-orphan the author page** — link from About, blog index, every post,
   every product page. An orphaned author page carries no weight.
8. **Close the entity graph:** `Person → worksFor → Organization → url →
   WebSite`; `Article → author → Person`.

Two hard-won details:

> A graceful *visual* fallback (`onerror` → logo) does **not** fix a *schema*
> reference. The referenced `Person.image` URL must return 200.

> `curl -I` the author URL **live**. The reference author page was complete in
> the repo and 404'd in production — 76 pages linked to a broken author page.

Never fabricate the rating, photo, or credential of that person. A fake `5.0`
on an author page was itself a finding — and `AggregateRating` on a `Person`
is invalid schema regardless.

---

## 7. Measurement

Direct rank tracking doesn't exist for AI answers. Use proxies:

| Signal | Where | Frequency |
|---|---|---|
| Rich results | GSC → Enhancements | weekly |
| FAQ rich results | GSC → Search Appearance | weekly |
| AI Overview appearance | manual query of target terms | weekly |
| Perplexity citations | `site:domain.com` in Perplexity | monthly |
| ChatGPT citations | ask target questions, look for the domain | monthly |
| Organic impressions | GSC → Performance | weekly |

Latency: first movement 2–4 weeks after schema deploy; full effect 8–12 weeks.

---

## 8. Checklist

- [ ] All major AI crawlers explicitly allowed in robots.txt
- [ ] `llms.txt` present, absolute URLs, grouped by intent
- [ ] Schema-without-visible-content set is empty
- [ ] Every commercial page has extractable Q&A, not just the blog
- [ ] Direct-answer block in the first 150 words on informational pages
- [ ] Every quotable passage passes the self-containment test
- [ ] Entity map written; every entity block has ≥1 verifiable fact
- [ ] Knowledge graph has edges, not just duplicated nodes
- [ ] Named human author with a real credential, visible byline, live profile URL
- [ ] `Person.image` resolves 200 in production
- [ ] Zero fabricated ratings, credentials, or photos
- [ ] Proxy measurement cadence set up
