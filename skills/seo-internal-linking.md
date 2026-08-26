---
name: seo-internal-linking
description: >
  Rebuild a site's internal link graph. Two-graph measurement (raw vs
  contextual), orphan detection that survives mega-menus, a scored semantic
  relatedness model for related-content blocks, anchor-text distribution
  rules, and the hard-won rule that the header is UX, not an SEO instrument.
---

# SEO Internal Linking Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

The whole methodology in one line:

> **Count only links inside `<main>`. Exclude nav, header, footer, and every
> sitewide boilerplate region.**

Google discounts repeated navigation links for authority flow. A page linked
from a 100-link mega-menu on all 126 pages shows **126 inbound links** and
**crawl depth 1** — both numbers are meaningless artifacts.

---

## 1. Build two graphs, always

| Graph | Includes | Used for |
|---|---|---|
| **Raw** | every `<a href>` | Crawlability, broken links, "can Googlebot reach it at all" |
| **Contextual** | only `<a>` inside `<main>` / article body | Orphans, authority flow, depth, priority |

**A page can be depth 1 in the raw graph and an orphan in the contextual
graph. That gap is the finding.**

---

## 2. Orphan detection

| Tier | Contextual inbound | Label |
|---|---|---|
| 0 | `0` | **ORPHAN** |
| 1 | `1–2` | **NEAR-ORPHAN / CRITICAL** |
| 2 | `3–7` | **WEAK** |
| 3 | `8+` | OK |

```python
import re, io, os, glob, collections

ROOT = "."
NAV_RE    = re.compile(r'<!--\s*Topbar\s*-->.*?</header>', re.S|re.I)
FOOTER_RE = re.compile(r'<footer\b.*?</footer>', re.S|re.I)
HREF_RE   = re.compile(r'<a\b[^>]*?href=["\']([^"\']+)["\'][^>]*>(.*?)</a>', re.S|re.I)
TAG_RE    = re.compile(r'<[^>]+>')

inbound  = collections.defaultdict(set)
anchors  = collections.defaultdict(list)
outbound = collections.defaultdict(set)

pages = [p.replace("\\","/") for p in glob.glob(os.path.join(ROOT,"**","*.html"), recursive=True)
         if "/vendor/" not in p and "/partials/" not in p]

def canon(src_page, href):
    if href.startswith(("http","mailto:","tel:","#","javascript:")): return None
    href = href.split("#")[0].split("?")[0]
    if not href: return None
    p = os.path.normpath(os.path.join(os.path.dirname(src_page), href)).replace("\\","/")
    # GOTCHA: normpath eats the trailing slash — re-append it
    if href.endswith("/") and not p.endswith("/"): p += "/"
    return p

for page in pages:
    html = io.open(page, encoding="utf-8").read()
    body = FOOTER_RE.sub("", NAV_RE.sub("", html))      # strip boilerplate FIRST
    for href, inner in HREF_RE.findall(body):
        t = canon(page, href)
        if not t: continue
        inbound[t].add(page)
        anchors[t].append(TAG_RE.sub("", inner).strip())
        outbound[page].add(t)

for page in sorted(pages):
    n = len(inbound.get(page, ())) or len(inbound.get(page.replace("index.html",""), ()))
    tier = "ORPHAN" if n==0 else "NEAR-ORPHAN" if n<=2 else "WEAK" if n<8 else "OK"
    print(f"{n:4d}  {tier:12s}  {page}")
```

Quick triage without a script:

```bash
# Is page X linked from anywhere at all?
grep -rn --include=*.html 'target-folder/' . | grep -v '/partials/'

# How many pages reference it?
grep -rlc --include=*.html 'href="[^"]*target-folder/' . | wc -l
```

> **The tell for a false-negative orphan: a page whose inbound count exactly
> equals the total page count is nav-only.** That single heuristic found all
> three CRITICAL orphans on the reference project.

---

## 3. Link depth

BFS from `/` **on the contextual graph**:

```python
import collections
def depth(outbound, home="index.html"):
    d, q = {home: 0}, collections.deque([home])
    while q:
        u = q.popleft()
        for v in outbound.get(u, ()):
            if v not in d:
                d[v] = d[u] + 1; q.append(v)
    return d
# Not in `d` = contextually unreachable = orphan.
# depth >= 4 needs a hub link.
```

**Target: every money page ≤ 3 contextual hops from home.** Detail pages
reached via their hub = depth 2–3, correct. A detail page at depth 5 means
its hub doesn't list it.

Report the **distribution**, not just the max — a flat depth histogram is the
mega-menu artifact signature.

---

## 4. Hub-and-spoke architecture

```
HEADER      = user navigation
              Optimized for humans. Carries NO SEO weight by design.

HUB PAGES   = the complete crawl surface
              Every hub lists ALL its children in-content.
              No child may depend on the nav for discovery.

IN-CONTENT  = topical authority
              Semantic related blocks, spoke→guide links, hub↔hub links.
              Target: median ~7 contextual inbound links per page.

None of the three layers depends on the others.
```

Rules:

1. **Completeness invariant.** Before touching the nav, verify every hub lists
   100% of its children in body content.
   ```bash
   for d in hub/*/; do
     grep -q "href=\"[^\"]*$(basename $d)/\"" hub/index.html || echo "MISSING: $d"
   done
   ```
2. **Every spoke links up** to its hub, in-body, descriptive anchor.
3. **Every hub links sideways** to 3–5 sibling hubs — the ones a user would
   actually compare, not all of them.
4. **Every spoke links laterally** to 4–6 semantically related spokes (§6).
5. **Every spoke links to ≤3 supporting informational pages.**
6. **Reciprocity for peer hubs** — bidirectional cross-links between siblings
   send a "these are distinct, not duplicates" signal.
7. **Minimum outbound:** every page ships ≥5 in-body outgoing links.
8. **Zero-inbound is allowed only for** robots-disallowed pages and
   legal/utility pages that are correctly footer-only. Document these as
   *accepted exceptions* rather than "fixing" them.

---

## 5. The mega-menu question — do not trim the header for SEO

The most counterintuitive finding in the corpus. The trim was proposed twice
and **rejected twice.**

| Stage | Nav links | Detail pages in nav | Outcome |
|---|---|---|---|
| Original | 99 | 76 | — |
| SEO trim | 29 | 0 | **Rejected** |
| Curated compromise | 87 | 36 | **Rejected** |
| Final | 99 | 76 | **Accepted** |

The SEO argument for trimming was real: with ~100 nav links on every page,
*every* link is boilerplate, so nothing carries a contextual signal. 19 pages
had a flat 112 inbound each while 20 money hubs had **0 contextual inbound**.

The rule that emerged:

> **The header is user navigation, not an SEO instrument. Do not trim it for
> SEO. Fix the link graph in the content layer instead.**
>
> A mega-menu is only a problem if the hubs don't independently list all
> children. If hub completeness holds, the mega-menu is redundant-but-harmless,
> and removing it costs real UX for a speculative crawl-budget gain.

Gate:

```
IF every hub lists all its children in-content
   AND contextual inbound links exist independent of nav
THEN nav size is a UX decision, not an SEO decision. Leave it.
ELSE fix hubs and in-content FIRST, then re-evaluate.
```

Treat nav reduction as requiring explicit sign-off, never an audit auto-fix.
If you must restore a nav, extract the original block from git rather than
recreating it from memory, then verify byte-identity.

---

## 6. Semantic related-content selection

Replace "alphabetical siblings from the same folder" with a scored model.
Alphabetical blocks were the diagnosed defect: a 3-day X→Y tour's related
block never contained the 2-day or 4-day X→Y tour — it never linked to the
actual comparison the user was making.

| Signal | Weight |
|---|---|
| Shared **strong entity** (specific destination/topic token) | **+6 each** |
| Same **origin / parent category** | **+4** |
| Same **product kind** (tour / day-trip / activity / trek) | **+4** |
| **Adjacent magnitude** (±1 on the primary numeric axis) | **+3.5** |
| Cross-category but **same headline entity** | **+2** |
| **Different product kind** | **−4** |
| **Magnitude gap > 5** | **−2** |

The four correction mechanisms matter more than the weights:

1. **Origin-token suppression.** If a token is both the origin and appears as
   a destination, discard it as a destination signal. Generic form: *strip
   signal tokens that are constant within the candidate's own scope.*
2. **Diversity cap.** Max N per source folder (4 default; 6 where intra-folder
   relation is genuinely correct). Without it the scorer converges back to
   alphabetical siblings.
3. **Reciprocity pass.** If A links to B, boost B→A — but **cap the boost
   (+4) and gate it behind a relevance floor (≥5.0)**. Uncapped reciprocity
   injects unrelated product types into blocks.
4. **Rotating tie-break.** Break ties by `candidate_index + source_page_index`,
   not alphabetically — otherwise every tie resolves to the same
   alphabetically-first page, which accumulates all the equity.

```python
def score(src, cand):
    if cand.id == src.id: return -999
    s = 0.0
    src_ents  = src.entities  - {src.origin}     # origin-token suppression
    cand_ents = cand.entities - {cand.origin}
    s += 6 * len(src_ents & cand_ents)
    if src.origin == cand.origin: s += 4
    if src.kind   == cand.kind:   s += 4
    else:                         s -= 4
    gap = abs(src.magnitude - cand.magnitude)
    if gap == 1: s += 3.5
    if gap >  5: s -= 2
    if src.headline_entity == cand.headline_entity and src.origin != cand.origin:
        s += 2
    return s

def select(src, pool, n=6, per_folder_cap=4):
    scored = sorted(pool, key=lambda c: (-score(src, c),
                                         (pool.index(c) + src.index) % len(pool)))
    out, seen = [], collections.Counter()
    for c in scored:
        if score(src, c) < 5.0: break            # relevance floor
        if seen[c.folder] >= per_folder_cap: continue
        out.append(c); seen[c.folder] += 1
        if len(out) == n: break
    return out
```

**Acceptance criterion:** every item has ≥2 contextual inbound links, and
blocks demonstrably cross category boundaries. Validate by printing sample
blocks and eyeballing for cross-folder entries.

### Entity-matched supporting-content links

A simpler rule table, capped at 3 per page:

| Guide | Trigger predicate |
|---|---|
| Route guide A→B | destination == B AND origin == A |
| Comparison X vs Y | X or Y in destinations |
| Seasonality guide | any page in category C |
| Cost guide | category C AND multi-unit |
| Duration guide | magnitude ≥ 5 |

---

## 7. Anchor text rules

- **Descriptive anchors only.** No "click here", "read more", "learn more".
- **Mix three types** per target: exact-match, partial/topical, branded.
- **No single anchor above ~20%** of a target's inbound anchors.
- Links go **into existing prose paragraphs** — intro/middle/closing — not
  appended as a link list.
- **3–5 in-body links per content page**, 2–3 per utility page.
- **Depth-aware relative paths** on static sites (`../../`), never
  root-relative, so `file://` previews still work.

```bash
grep -rho '<a [^>]*href="[^"]*{target}[^"]*"[^>]*>[^<]*</a>' --include=*.html . \
  | sed 's/.*>\(.*\)<\/a>/\1/' | sort | uniq -c | sort -rn
# top_count / total must be < 0.20
```

---

## 8. Two gotchas that caused real breakage

### A — Container-boundary detection by counting `<div>`

Rewriting a card carousel by counting `<div>` opens/closes left a stray
`</article></div>` on **56 pages**. Caught by tag-parity check, reverted via
`git checkout`.

**Correct approach: don't find the container. Find the run of repeated
sibling blocks and replace first-start to last-end.**

```python
# WRONG: count <div> to find where the wrapper ends
# RIGHT:
spans = [m.span() for m in re.finditer(r'<article class="tour-card".*?</article>', html, re.S)]
if spans:
    start, end = spans[0][0], spans[-1][1]
    html = html[:start] + new_cards_html + html[end:]
```

Always follow a bulk edit with a parity assertion:

```python
import re, glob, io
for f in glob.glob("**/*.html", recursive=True):
    h = io.open(f, encoding="utf-8").read()
    for tag in ("div","article","section","ul","li"):
        o = len(re.findall(rf'<{tag}\b', h)); c = len(re.findall(rf'</{tag}>', h))
        if o != c: print(f, tag, o, c)
```

### B — `os.path.normpath` strips trailing slashes

`os.path.normpath("/tour-x/")` → `"/tour-x"`. Where canonical URLs are
directory URLs with a trailing slash, this causes a **301 redirect on every
generated card link** — no visible breakage, silent link-equity loss and
crawl waste across the whole site.

```python
p = os.path.normpath(os.path.join(base, href)).replace("\\", "/")
if href.endswith("/") and not p.endswith("/"):
    p += "/"
```

Regression test:

```bash
grep -rho 'href="[^"]*"' --include=*.html . \
  | grep -v '\.html\|\.webp\|\.css\|\.js\|#\|http\|mailto\|tel' | grep -v '/"'
```

### Related

- **Sitewide sync scripts can silently revert same-session work.** Prove one
  touches only what it claims: SHA-256 every page's body *with nav and footer
  stripped*, before and after. Report `pages whose BODY changed: 0 (of N)`.
- **Minified assets.** If pages load `*.min.css`/`*.min.js`, mirror every
  source edit into the `.min` file and regenerate `.br`/`.gz` siblings.
- **Never add `aria-expanded` without JS to update it** — a static
  `aria-expanded="false"` actively misreports state and is worse than omitting
  the attribute.

---

## 9. Checklist

- [ ] Two graphs built; orphans measured on the **contextual** one
- [ ] nav/header/footer stripped before counting
- [ ] `normpath` trailing-slash fix applied
- [ ] Inbound-count-equals-page-count heuristic run (nav-only detection)
- [ ] Every hub lists 100% of its children in body content
- [ ] Every money page ≤ 3 contextual hops from home
- [ ] Every page ≥5 in-body outbound links
- [ ] Related blocks scored semantically, cross-folder entries present
- [ ] Diversity cap, relevance floor, capped reciprocity, rotating tie-break all in
- [ ] No single anchor text >20% for any target
- [ ] Tag parity verified after every bulk edit
- [ ] Accepted zero-inbound exceptions documented with reasons
- [ ] Header NOT trimmed for SEO without explicit sign-off
