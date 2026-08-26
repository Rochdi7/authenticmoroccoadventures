# SEO Skills — portable pack

Eleven SEO skills distilled from ~40 audit, implementation and verification
reports produced for a 126-page production site across seven passes
(Apr–Aug 2026). Everything site-specific has been stripped. Copy this folder
into any project.

**These are not generic SEO advice.** Every rule here is something that was
measured, got something wrong, or cost real time on a real site. The gotchas
are the point.

**Works on any stack.** The methods were derived on a static HTML site, so the
commands are written that way — but
[seo-project-adapter.md](seo-project-adapter.md) maps every one of them onto
Laravel/Blade, WordPress, Next.js, Rails, Django, Astro and friends. On any
dynamic project, read that first.

---

## The skills

| Skill | Use when |
|---|---|
| **[seo-project-adapter](seo-project-adapter.md)** | **Read first on any non-static project.** Stack detection, rendered-corpus capture, template-vs-instance |
| **[seo-verification](seo-verification.md)** | **Read first when acting on an audit** — including your own |
| [seo-technical-audit](seo-technical-audit.md) | Crawling, indexing, canonicals, redirects, GSC recovery |
| [seo-keyword-research](seo-keyword-research.md) | Per-page targeting, titles/meta, keyword ownership |
| [seo-cannibalization](seo-cannibalization.md) | Duplicate/competing pages, thin content |
| [seo-internal-linking](seo-internal-linking.md) | Orphans, link graph, related-content blocks, nav decisions |
| [seo-schema](seo-schema.md) | JSON-LD deployment, validation, NAP truthfulness |
| [seo-geo-ai-search](seo-geo-ai-search.md) | AI Overviews, ChatGPT/Perplexity citations, E-E-A-T |
| [seo-content-strategy](seo-content-strategy.md) | Topic selection, briefs, editorial passes |
| [seo-performance](seo-performance.md) | PageSpeed/Core Web Vitals without visual change |
| [seo-reporting](seo-reporting.md) | Structuring audits and client deliverables |

---

## Starting on a new project

[PROMPT-FOR-NEW-PROJECT.md](PROMPT-FOR-NEW-PROJECT.md) is a ready-to-paste
prompt that hands this whole pack to Claude on another codebase, sets caveman
mode for the working chat, and specifies a client-ready report as the
deliverable. Copy `skills/` into the target project, then paste that prompt.

---

## Recommended order for a full site pass

```
0. seo-project-adapter   ← unless the project is plain static HTML
1. seo-verification      ← if acting on an existing audit, ALWAYS start here
2. seo-technical-audit   ← structure before content, always
3. seo-cannibalization   ← resolve competing pages before optimizing them
4. seo-keyword-research  ← assign one keyword per page
5. seo-internal-linking  ← fix the link graph
6. seo-schema            ← structured data, truthfully
7. seo-geo-ai-search     ← AI citability
8. seo-content-strategy  ← fill the gaps
9. seo-performance       ← CWV
10. seo-reporting        ← write it up
```

Steps 2 and 3 are load-bearing. **Content work on a page with a conflicting
canonical or an unresolved duplicate produces zero measurable movement**, and
the failure is invariably misattributed to "Google being slow."

---

## The seven rules that cut across every skill

**1. Structure before content.**
A conflicting head signal makes all content work unmeasurable. If GSC's
"Validate fix" fails after a content pass, the content was never the problem.

**2. Repo ≠ production.**
On a mature repo, the most common "critical bug" is a deploy gap. `curl` the
live URL *and* read the local file before writing a single fix. Classify every
finding as source bug / deploy gap / host-infra / tool artifact.

On a dynamic stack this becomes **three** states to diff: template → rendered
output → production. **Audit the rendered output; fix the template.** One
broken template is *one* finding affecting N URLs — report it that way, not as
N findings.

**3. Verify before you act.**
An audit finding is a hypothesis. Re-measure it with a *structurally
different* method. On the reference project, **6 of the top findings were
false positives** — including the headline "89% of pages have no canonical
tag", which was a regex that couldn't match multi-line `<link>` tags. The real
number was 7, all error pages.

**4. Never fabricate data.**
Not a rating, review count, price, coordinate, address, credential, or quote.
If real data is unavailable, **omit the property**. A missing
`aggregateRating` costs a star snippet; a fake one can cost the site. The
reference project shipped `"reviewCount": "200"` when the truth was 20, and
`"streetAddress": "Marrakech"` — a city, not an address — across 65 files.

**5. Schema must match visible content.**
69 pages carried FAQ schema with no visible FAQ. It's a policy violation, it
generates no rich result, and fixing it (rendering the Q&A) was simultaneously
the biggest GEO win of the entire project.

**6. Check for regressions before "fixing" what's missing.**
124 images were flagged as "missing `loading`". All were above-the-fold.
Absent `loading` means eager, which is *correct* there — adding lazy would
have hurt LCP. Correct outcome: no change made.

**7. Escalate architecture decisions; don't make them unilaterally.**
Merges, redirects, canonical choices, and nav removal need owner approval and
real data. A cannibalization check that concludes "no merge" is a valid and
valuable output.

---

## Universal safety rules for bulk edits

Any script touching many files — HTML pages, Blade views, or DB rows:

1. **Commit first.** `git checkout` is the undo.
2. **Idempotent by construction** — check-then-insert, never blind-insert. A
   bulk head-tag script that inserts without checking will silently double the
   tag on every page it touches. This is how the reference site got duplicate
   canonicals.
3. **UTF-8 explicitly.** Python `encoding='utf-8'`; PowerShell
   `[System.IO.File]::ReadAllText/WriteAllText` with
   `[System.Text.UTF8Encoding]::new($false)`. **Never**
   `Get-Content`/`Set-Content` — they mojibaked 117 files in one pass.
4. **Never `os.path.normpath` a URL without re-appending the trailing slash.**
   It turns `/tour-x/` into `/tour-x` → a 301 on every generated link.
5. **Replace spans, not containers.** To rewrite a block of repeated siblings,
   find the run of `<article>…</article>` and replace first-start to last-end.
   Counting `<div>` left stray closing tags on 56 pages.
6. **Verify after:** tag parity, JSON-LD parses, zero mojibake, count-per-file
   assertions.
7. **Regenerate derived artifacts.** If the site serves `.min` bundles or
   `.br`/`.gz` siblings, a source-only edit does not ship.
8. **Re-verify after any template-sync script runs** — it can silently revert
   same-session work.
9. **On dynamic stacks:** prefer editing **one template** over scripting N
   rendered pages. For data fixes use a reversible migration with
   `chunkById` + `saveQuietly()`, run it on staging first, and diff the
   rendered output before and after.
10. **Clear the caches** the framework owns — compiled views, route/config
    cache, CDN, page cache. An edit that works locally and not in production
    is usually a cache, not a bug.

```bash
# post-edit verification battery
grep -rl 'â€\|Ã©\|â€™' --include=*.html .          # mojibake — expect nothing
git diff --stat HEAD                                # symmetric +/- = text-only pass
```

---

## Standing metrics to report as hard zeros

```
Broken internal links .............. 0
Duplicate titles ................... 0
Duplicate meta descriptions ........ 0
JSON-LD blocks ..................... N  (0 invalid)
Tag imbalances ..................... 0
Images missing alt ................. 0
Images missing dimensions .......... 0
Fabricated-data leftovers .......... 0
Mojibake ........................... none
Orphan pages ....................... 0  (excl. documented exceptions)
```

**An unexplained non-zero is a bug report; an explained non-zero is a
finding.** Always annotate.

---

## Provenance

Distilled from `docs/reports/` on the Local Morocco Tours project — including
`SEO_PASS.md`, `GEO_PASS.md`, `KEYWORD_UPGRADE_PILOT`,
`INDEPENDENT_VERIFICATION_AUDIT`, `SEO_INTERNAL_LINKING_AND_HEADER`,
`PERFORMANCE_PASS`, `JULY_2026_AUDIT_IMPLEMENTATION`, and
`LOCAL-MOROCCO-TOURS-SEO-AUDIT.md` with its correction log.

The single most valuable source was the **independent verification audit**,
which treated a prior report as an unverified claim and checked every
statement against `git diff` and the actual files. That posture is encoded in
`seo-verification.md` and is the skill to read first.
