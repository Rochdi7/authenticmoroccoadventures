---
name: seo-verification
description: >
  Verify every SEO audit finding before acting on it. Catches regex false
  positives, live-vs-repo deployment gaps, and "fixes" that are actually
  regressions. Run this BETWEEN any audit and any implementation pass.
  Derived from a real audit where 6 of the top findings — including the
  headline "89% of pages have no canonical tag" — were wrong.
---

# SEO Verification Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

**Load this whenever you are about to implement someone else's SEO audit —
including one you wrote yourself in an earlier session.**

The rule this skill exists to enforce:

> An audit finding is a **hypothesis**, not a fact. Verify it against the
> actual bytes before you change a single file.

On the reference project, a professional-looking audit scored the site
63/100 and listed 5 "critical" issues. Independent verification found that
**6 of its headline findings were false positives**. Implementing them
blindly would have caused at least one real performance regression.

---

## 0. Why audits are wrong — the four failure modes

| Mode | What happens | How to catch it |
|---|---|---|
| **Regex too strict** | Detector matches only one formatting variant; the site uses another | Re-check with a whitespace/newline-insensitive pattern, then eyeball 3 raw files |
| **Live-vs-repo gap** | Auditor checked production; repo is ahead (or behind) | `curl` the live URL AND read the local file. Compare explicitly. |
| **Missing intent** | A "missing" attribute is deliberately absent and correct | Ask "what breaks if I add this?" before adding it |
| **Stale snapshot** | Report was generated before a deploy/edit mid-session | Compare report timestamp vs `git log` / file mtime / live `Last-Modified` |

---

## 1. The verification loop — run per finding

For **every** finding, in order:

```
1. RESTATE the claim as a testable assertion, with a number.
      "115 of 126 pages are missing <link rel=canonical>"
2. RE-MEASURE it yourself with an independent method (different regex,
      different tool, different parser).
3. COMPARE the two numbers.
      match      -> finding is REAL, proceed
      mismatch   -> finding is a FALSE POSITIVE, document and skip
4. If REAL: check LOCATION — is it a source defect or a deploy gap?
5. If REAL + source defect: check INTENT — would fixing it regress anything?
6. Only now: fix.
```

**Never collapse steps 2 and 6.** The moment you "fix while checking", you
lose the ability to tell a real defect from a detector bug.

---

## 2. Step 2 in practice — independent re-measurement

The cardinal sin is re-using the audit's own detection logic. Use a
**structurally different** method.

### Multi-line tags — the #1 false-positive source

Most hand-written and prettier-formatted HTML wraps attributes:

```html
<!-- The audit's regex looked for this -->
<link rel="canonical" href="https://example.com/page/">

<!-- The site actually used this -->
<link
  rel="canonical"
  href="https://example.com/page/"
/>
```

A regex like `<link rel="canonical"` misses 100% of the second form.

**Always re-check with a DOTALL / multi-line-tolerant pattern:**

```bash
# WRONG — single-line only, produces the false positive
grep -L 'rel="canonical"' *.html

# RIGHT — tolerant of newlines and attribute order
python3 - <<'PY'
import re, pathlib
pat = re.compile(r'<link\b[^>]*\brel=["\']canonical["\']', re.I | re.S)
missing = [str(p) for p in pathlib.Path('.').rglob('*.html')
           if not pat.search(p.read_text(encoding='utf-8', errors='replace'))]
print(len(missing), 'missing')
for m in missing[:20]: print(' ', m)
PY
```

Note `[^>]*` **plus** `re.S` — attributes may span newlines but stay inside
one tag.

**Better still: parse, don't regex.** If the environment allows it:

```bash
python3 -c "
from bs4 import BeautifulSoup; import pathlib
for p in pathlib.Path('.').rglob('*.html'):
    s = BeautifulSoup(p.read_text(encoding='utf-8', errors='replace'), 'html.parser')
    if not s.find('link', rel='canonical'): print(p)
"
```

Apply the same tolerance to every tag-presence check: `meta description`,
`og:*`, `hreflang`, `robots`, JSON-LD blocks, `<h1>`.

### Sanity-check the count against reality

If a finding says "89% of pages are missing X", open **three random pages**
and look with your eyes. A defect that affects 89% of a maintained site is
extraordinary; extraordinary claims need a manual spot-check.

> Reference case: claim was 115/126 missing canonicals. Truth was **7** —
> and all 7 were error pages (400/401/403/404/500/502/504), which are
> `noindex` anyway. Severity: "single biggest technical finding" → trivial.

---

## 3. Step 4 — source defect vs deployment gap

**This distinction changes who fixes it and how.** A deploy gap cannot be
fixed by editing HTML, and "fixing" it in the repo produces no change at all.

Run the comparison explicitly:

```bash
URL=https://www.example.com
P=/some/page.html

# What does production actually serve?
curl -sI  "$URL$P"                     # status, Last-Modified
curl -s   "$URL$P" | grep -c canonical # content check on LIVE

# What does the repo contain?
grep -c canonical ".$P"

# Is production simply stale?
curl -sI "$URL$P" | grep -i last-modified
git log -1 --format=%ci -- ".$P"
```

Decision table:

| Repo | Live | Verdict | Action |
|---|---|---|---|
| correct | correct | Not an issue | Document "no fix needed" |
| correct | wrong | **Deploy gap** | Do NOT edit code. Escalate to whoever deploys. |
| wrong | wrong | **Source defect** | Fix in repo |
| wrong | correct | Repo regression | Fix repo to match; find out what reverted it |

> On the reference project, three "critical" findings — missing sitemap
> URLs, a 404ing author page, and absent preload tags — were **all**
> deploy gaps. The repo was correct in every case. The site consistently
> ran ahead of production, so any audit that only checked live produced
> phantom findings.

**Rule:** on a project where the repo is routinely ahead of production,
diff live vs local *before* trusting any audit finding.

Report deploy gaps in their own section. Never silently drop them — but
never bill them as code fixes either.

---

## 4. Step 5 — the regression check

Before adding any attribute "because it's missing", ask what its absence
was doing.

### The `loading="lazy"` trap

An audit flagged 124 images as "missing `loading` attribute". All 124 were
above-the-fold (123 site logos + 1 hero with `fetchpriority="high"`).

Browsers eager-load by default when `loading` is absent — **that is correct
for above-fold images.** Adding `loading="lazy"` would have delayed the
LCP element and *lowered* the performance score.

**Correct outcome: no change made.**

Generalize this. Before adding, confirm the element is actually below the
fold:

```bash
# Which images sit before the first </header> or in the hero?
python3 - <<'PY'
import re, pathlib
for p in list(pathlib.Path('.').rglob('*.html'))[:5]:
    t = p.read_text(encoding='utf-8', errors='replace')
    head = t[:t.find('</header>') + 9] if '</header>' in t else t[:3000]
    print(p, '->', len(re.findall(r'<img\b', head)), 'above-fold imgs')
PY
```

### Other "fixes" that are regressions

| Proposed fix | When it's wrong |
|---|---|
| `loading="lazy"` on all images | Above-fold / LCP images — hurts LCP |
| Add canonical to every page | Paginated or filtered URLs may need self-canonical or none; never point them all at page 1 blindly |
| `noindex` thin pages | If they have inbound links/traffic, improve instead |
| Consolidate "duplicate" pages | If intents genuinely differ, merging destroys two rankings |
| Add `alt` text everywhere | Decorative images should have **empty** `alt=""`, not invented descriptions |
| Minify/inline everything | Can break cache strategy and increase HTML weight per page |
| Add `hreflang` | Wrong/partial hreflang is worse than none |

---

## 5. Never fabricate data — the hard rule

The most damaging finding on the reference project was **schema containing
invented numbers**. This is a manual-action risk, not a style problem.

What was found:

- `"ratingCount": "200"` / `"reviewCount": "200"` — **fabricated**; the real
  Google Business Profile had **20**.
- `"streetAddress": "Marrakech"` on 65 files, `"Marrakech Medina"` on 2 —
  neither was the real address.
- Visible review widgets showed random counts (37, 41, 168, 197) that
  contradicted the schema value on 100+ pages.
- An author page carried an `AggregateRating` for a **person** — invalid and
  invented.

### Rules

1. **Never invent** a rating, review count, price, coordinate, date,
   certification, or address. Not even a plausible one.
2. **Schema must match what is visible on the page.** If schema says 20
   reviews, the page must show 20. A mismatch is a trust red flag and is
   independently detectable by Google.
3. If real data is unavailable, **omit the property**. An absent
   `aggregateRating` costs you a star snippet. A fake one costs you the site.
4. Get NAP (Name/Address/Phone) from the **real Google Business Profile**,
   and use the identical string everywhere.
5. `GeoCoordinates` — only from the real GBP pin. Do not approximate from a
   city centre.

Verification sweep:

```bash
# Every rating/review number in schema, with its file — eyeball for consistency
grep -rn '"ratingCount"\|"reviewCount"\|"ratingValue"' --include=*.html . \
  | sed 's/^\([^:]*\).*"\(rating\|review\)[A-Za-z]*": *"\?\([0-9.]*\)/\1  \2 = \3/' \
  | sort | uniq -c | sort -rn

# Do visible counts agree with schema counts?
grep -rn 'reviews\?\b' --include=*.html . | grep -oE '[0-9]{2,4} reviews?' | sort | uniq -c

# Address consistency
grep -rh '"streetAddress"' --include=*.html . | sort | uniq -c | sort -rn
```

More than one distinct address or review count across the site = defect.

---

## 6. Invisible structured data

Schema describing content that **does not exist visibly on the page**
violates Google's structured-data policy and generates no rich result.

On the reference project, **69 pages had `FAQPage` JSON-LD with no visible
FAQ anywhere in the body.**

```bash
# Pages with FAQ schema
grep -rl '"@type": *"FAQPage"' --include=*.html . | sort > /tmp/faq_schema.txt

# Pages with a plausible visible FAQ block
grep -rl -iE 'class="[^"]*faq|<h[23][^>]*>[^<]*\?[[:space:]]*</h[23]>' --include=*.html . | sort > /tmp/faq_visible.txt

# Schema without visible content — the violation set
comm -23 /tmp/faq_schema.txt /tmp/faq_visible.txt
```

**Two valid fixes:** render the Q&A visibly in the body, or remove the
schema. Never leave schema-only.

Same test applies to `Review`, `HowTo`, `Recipe`, `Event`, and
`AggregateRating` — all require visible on-page counterparts.

---

## 7. Verification report format

Produce this table **before** implementing. It is the deliverable of this
skill.

```markdown
## Corrections to the original audit (found during verification)

| Audit claim | Reality | Why the audit was wrong |
|---|---|---|
| "115/126 missing canonical" | Only **7** (error pages only) | Detector regex matched single-line `<link>` only; site uses multi-line format |
| "4 blog posts missing from sitemap" | Present in repo sitemap (112 URLs) | Audit checked the **live** sitemap, which lags the repo — deploy gap, not a source defect |
| "124 images missing loading attr" | All 124 are above-the-fold | Absent `loading` = eager = correct for LCP images. Adding lazy would regress. |
| "Blog has zero nav links" | Footer link already existed | Partial false positive — only the header was missing it |
```

Then classify every finding into exactly one bucket:

- **CONFIRMED — fixing now** (real, source-level, no regression risk)
- **CONFIRMED — needs server/hosting access** (deploy gap; who owns it)
- **CONFIRMED — needs real data from the client** (never fabricate)
- **FALSE POSITIVE — no fix needed** (with the reason)
- **WOULD BE A REGRESSION — deliberately not fixed** (with the reason)

Every original finding must appear in exactly one bucket. Silent drops are
how audits lose trust.

---

## 8. Post-implementation re-measurement

Re-measure on the **final** state of the tree, not from your change log.
Numbers you predicted are not numbers you observed.

```
Pages crawled ..................... 126
Broken internal links .............. 0
URLs changed ....................... 0
Duplicate titles ................... 0
Duplicate meta descriptions ........ 0
JSON-LD blocks ..................... 488  (0 invalid)
Tag imbalances ..................... 0
Sitemap ............................ 112 / 112
Images missing alt ................. 0
Images missing dimensions .......... 0
Mojibake ........................... none
```

Validate every JSON-LD block actually parses:

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
            bad += 1; print('INVALID', p, str(e)[:80])
print(f'{tot} blocks, {bad} invalid')
PY
```

Check tag parity after any bulk edit:

```bash
python3 - <<'PY'
import re, pathlib
for p in pathlib.Path('.').rglob('*.html'):
    t = p.read_text(encoding='utf-8', errors='replace')
    for tag in ('div','article','section'):
        o = len(re.findall(rf'<{tag}\b', t)); c = len(re.findall(rf'</{tag}>', t))
        if o != c: print(f'{p}: <{tag}> {o} open / {c} close')
PY
```

And confirm no mojibake was introduced:

```bash
grep -rl 'â€\|Ã©\|â€™\|â€"' --include=*.html . && echo "MOJIBAKE FOUND" || echo "clean"
```

---

## 9. Checklist

- [ ] Every finding restated as a numeric, testable assertion
- [ ] Every finding re-measured with a **structurally different** method
- [ ] Multi-line tag formats accounted for in every presence check
- [ ] 3 random pages manually eyeballed for any >50%-of-site claim
- [ ] Live vs repo compared for every finding (`curl` + local read)
- [ ] Deploy gaps separated from source defects
- [ ] Regression check done before adding any "missing" attribute
- [ ] Zero fabricated numbers; schema matches visible content
- [ ] Schema-without-visible-content set is empty
- [ ] Corrections table written **before** implementation
- [ ] Every original finding lands in exactly one bucket
- [ ] Final numbers re-measured on the finished tree, not predicted
- [ ] JSON-LD parses, tags balance, no mojibake
