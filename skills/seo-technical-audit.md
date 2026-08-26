---
name: seo-technical-audit
description: >
  Technical SEO and indexing audit for any site. Runs a 6-stage diagnostic in
  dependency order — server truth, inventory reconciliation, head-tag integrity,
  duplication, link graph, schema parity — then maps each finding to a fix
  pattern. Includes the GSC remediation loop and the "structure before content"
  rule that prevents wasting weeks on unmeasurable content work.
---

# SEO Technical Audit Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

**Run the stages in order.** Each is cheap and gates the next. Running them
out of order is how projects lose weeks: content remediation on a page with
a conflicting canonical produces exactly zero movement, and the failure is
invariably misattributed to "Google being slow."

> **Structure before content.** A conflicting head signal makes all content
> work unmeasurable.

---

## Stage 0 — Establish which reality you are auditing

The most important lesson in this entire skill: **repo ≠ production.**

Classify every finding before writing it up:

| Class | Meaning | Fix owner |
|---|---|---|
| **Source bug** | Wrong in the repo | Code |
| **Deploy gap** | Right in repo, absent or stale live | Release process |
| **Host/infra** | Right in repo, silently overridden by the server | Hosting ticket / CDN |
| **Tool artifact** | The auditor can't resolve the pattern | Nothing — document it |

```bash
D=https://www.example.com
P=/path/page.html

curl -sI "$D$P" | head -1                              # exists live?
curl -s  "$D$P" | wc -c ; wc -c < ".$P"                # same bytes?
curl -s  "$D/" | grep -o '<title>[^<]*</title>'        # shipped title live?
curl -sI "$D$P" | grep -i last-modified
git log -1 --format=%ci -- ".$P"                       # is live simply stale?
```

On a mature repo, the most common "critical bug" is a deploy gap.

---

## Stage 1 — Server-layer truth (5 minutes, `curl -I` only)

Confirm the server does what the config claims **before touching HTML**.

```bash
D=https://www.example.com

# Canonical host/protocol — how many hops from bare http?
curl -sIL -o /dev/null -w '%{num_connects} hops -> %{url_effective} (%{http_code})\n' http://example.com/
# Expect ONE 301. Two hops (http->https->www) is default in most stacks — collapse it.

curl -I $D/index.html            # expect 301 -> /
curl -I $D/                      # expect 200
curl -I $D/legacy-ghost.htm      # expect 301 or 410, never 200

# Compression actually negotiated?
curl -sI -H 'Accept-Encoding: br, gzip' $D/assets/css/site.css | grep -iE 'content-encoding|content-length|vary'
curl -sI $D/assets/css/site.css.br | grep -iE 'content-encoding|content-length'

curl -sI $D/ | grep -iE 'strict-transport|content-security|x-frame|x-content-type|referrer-policy'
curl -sI $D/ | grep -i '^server:'      # what stack is actually in front of you?
```

Ghost-URL interpretation:

| Response | Meaning |
|---|---|
| 301 | Redirect config deployed and running — fine |
| 404 | Config missing, or the host ignores `.htaccess` (nginx) |
| 200 | Legacy files never deleted at deploy — worst case |

**`Vary: Accept-Encoding` present ≠ compression working.** It means
negotiation is *configured*, not that it *functions*.

---

## Stage 2 — Inventory reconciliation

Three sets must agree: files on disk, URLs in the sitemap, URLs returning
200 live.

```bash
find . -name '*.html' -not -path './vendor/*' -not -path './partials/*' | sort > /tmp/disk.txt
grep -o '<loc>[^<]*</loc>' sitemap.xml | sed 's/<\/\?loc>//g' | sort > /tmp/sitemap.txt
while read u; do printf '%s %s\n' "$(curl -s -o /dev/null -w '%{http_code}' "$u")" "$u"; done < /tmp/sitemap.txt > /tmp/live.txt
grep -v '^200 ' /tmp/live.txt     # every line is a sitemap defect
```

| Defect | Fix |
|---|---|
| In sitemap, not 200 live | Remove from sitemap, or fix/deploy the page |
| 200 live, not in sitemap | Sitemap-orphaned — slows discovery. Add it. |
| On disk, not live | Deploy gap |

Sitemap hygiene rules:

- Never list error pages, redirect stubs, `noindex` pages, or URLs that redirect.
- Each entry must be byte-identical to that page's own canonical (protocol,
  host, trailing slash, no `index.html`).
- **Never remove a page from the sitemap because GSC says it isn't indexed** —
  that deletes the discovery signal.

---

## Stage 3 — Head-tag structural integrity

This is where root causes hide after failed remediation passes.

```bash
# a) DUPLICATE canonical tags — the killer finding
for f in $(find . -name '*.html'); do
  n=$(grep -c 'rel="canonical"' "$f"); [ "$n" -ne 1 ] && echo "$n $f"
done

# b) canonical value sanity
grep -rho 'rel="canonical" href="[^"]*"' --include='*.html' . | sort -u | grep -vE 'https://www\.example\.com/'
grep -rl 'rel="canonical" href="[^"]*index\.html"' --include='*.html' .

# c) unintended noindex
grep -rn 'name="robots"' --include='*.html' . | grep -i noindex

# d) multiple/zero H1
for f in $(find . -name '*.html'); do
  n=$(grep -o '<h1' "$f" | wc -l); [ "$n" -ne 1 ] && echo "$n $f"
done
```

**Threshold: exactly one canonical per page. Two *identical* canonicals is
still a failure** — they need not contradict to be read as a conflicting
signal, and this alone can suppress a site's highest-value URLs while every
other metric looks healthy.

> Mechanism worth memorizing: **a bulk edit that inserts a head tag without
> first checking whether it already exists will silently double it across
> every page it touches.** Every head-tag script must be idempotent by
> construction — check-then-insert, never blind-insert — and must be
> followed by a count-per-file verification.

⚠️ **Detection caveat:** `grep -c 'rel="canonical"'` misses multi-line
`<link>` tags. See `seo-verification.md` — a "89% of pages missing canonical"
finding on the reference project was a regex artifact; the real number was 7.
Always re-check presence with a DOTALL-tolerant pattern before acting.

---

## Stage 4 — Duplication and cannibalization

Only after 1–3 are clean.

```bash
grep -rho '<title>[^<]*</title>' --include='*.html' . | sort | uniq -d
grep -rhoP '(?<=name="description" content=")[^"]*' --include='*.html' . | sort | uniq -d
grep -rhoP '(?<=<h1[^>]*>)[^<]*' --include='*.html' . | sort | uniq -d
```

Cannibalization detector — group by the first 4 normalized title words:

```python
import re, glob, collections
groups = collections.defaultdict(list)
for f in glob.glob('**/*.html', recursive=True):
    h = open(f, encoding='utf-8').read()
    m = re.search(r'<title>(.*?)</title>', h, re.S)
    if not m: continue
    w = re.sub(r'[^a-z0-9 ]', '', m.group(1).lower()).split()
    if len(w) >= 4:
        groups[' '.join(w[:4])].append((f, m.group(1)))
for k, v in groups.items():
    if len(v) > 1:
        print(k, len(v))
        for a, b in v: print('   ', a, '->', b)
```

Head-term saturation — how many pages lead with the same money phrase:

```bash
grep -ric '<title>[^<]*MONEY PHRASE' --include='*.html' .
grep -rn  '<h1[^>]*>[^<]*MONEY PHRASE' --include='*.html' .
```

**Triage rule — not every group is real cannibalization:**

- **Real** — different page *types* competing on identical intent (a hub and
  a listing both titled with the same generic phrase). Fix.
- **Structural** — same template, genuinely distinct entities differing at
  word 5+ (`3-Day X to A` vs `3-Day X to B`). Accept and **document why**.
  Rewriting these creates churn for zero gain.

Fix pattern for real ones: **rewrite titles entity-first, not category-first.**
`CATEGORY: N Days A to B` → `N-Day A to B CATEGORY | Brand`. This
mechanically breaks the shared prefix and moves the differentiating entity
into the highest-weighted position.

Title, H1, meta, and first paragraph must **move together** — changing the
title alone leaves the body contradicting it.

---

## Stage 5 — Internal link graph

```python
import re, glob, os, collections
pages = [p.replace('\\','/') for p in glob.glob('**/*.html', recursive=True)]
inb, outb = collections.Counter(), collections.Counter()
for f in pages:
    h = open(f, encoding='utf-8').read()
    body = h[h.find('<body'):]
    # STRIP nav/header/footer — boilerplate links are not editorial signal
    body = re.sub(r'<(header|footer|nav)\b.*?</\1>', '', body, flags=re.S|re.I)
    for href in re.findall(r'href="([^"#?]+)"', body):
        if href.startswith(('http','mailto:','tel:','//')): continue
        t = os.path.normpath(os.path.join(os.path.dirname(f), href)).replace('\\','/')
        if t.endswith('/'): t += 'index.html'      # normpath strips the slash — re-add
        if os.path.isdir(t): t += '/index.html'
        if t in pages:
            inb[t] += 1; outb[f] += 1
for p in pages:
    if inb[p] == 0: print('ORPHAN', p)
    elif inb[p] < 5: print('WEAK', inb[p], p)
    if outb[p] < 5: print('LOW-OUT', outb[p], p)
```

**Thresholds:** `inbound == 0` → orphan (FAIL). `inbound < 5` → weak
(WARNING). body `outbound < 5` → under-linked (WARNING).

Three gotchas baked into the code above:

1. **Strip header/footer/nav before counting.** Otherwise every page looks
   like it has hundreds of links and orphans become invisible. Only body
   links are editorial signal.
2. **`os.path.normpath` strips trailing slashes** — silently turns `../hub/`
   into a non-matching key and reports real hubs as orphans. Re-append.
3. Depth-relative hrefs (`../../index.html`) make naive resolvers report the
   **homepage** as an orphan. That is a tool artifact — verify before acting.

Fix: rescue orphans with **body** links from thematically adjacent pages.
Adding a nav item alone does not fix editorial-signal starvation. Target 2–3
contextual body links per orphan.

---

## Stage 6 — Schema ↔ visible content parity

High severity: this is manual-action risk, not a ranking nudge.

```bash
grep -rl '"@type"\s*:\s*"FAQPage"' --include='*.html' . | sort > /tmp/faq_schema.txt
grep -rl -iE '<details|class="[^"]*faq|<h[23][^>]*>[^<]*\?' --include='*.html' . | sort > /tmp/faq_visible.txt
comm -23 /tmp/faq_schema.txt /tmp/faq_visible.txt   # invisible structured data

# numeric parity between schema and rendered page
grep -rhoP '(?<="reviewCount":\s")[0-9]+' --include='*.html' . | sort | uniq -c
grep -rhoP '\(\s*[0-9]+\s*Reviews?\s*\)' --include='*.html' . | sort | uniq -c

# every schema-referenced asset must resolve
grep -rhoP '(?<="image":\s")[^"]+' --include='*.html' . | sort -u | while read i; do
  [ -f "${i#/}" ] || echo "MISSING $i"; done
```

**Rule: structured data must never assert something the page doesn't visibly
show.** Two legitimate fixes — add the visible UI, or delete the schema.
Never leave the mismatch. Full treatment in `seo-schema.md`.

---

## Root causes and fix patterns

| # | Root cause | Detection | Fix |
|---|---|---|---|
| 1 | Duplicate canonical from non-idempotent bulk edit | count/file ≠ 1 | Remove older tag; make scripts check-then-insert |
| 2 | Missing canonicals at scale | count == 0 | Scriptable — each page's canonical *is* its sitemap URL |
| 3 | Legacy ghost URLs eating crawl budget | `curl -I` ≠ 301 + not on disk | 301 if equity plausible; **410 if long-dead** (301 keeps ghosts indexed for months). **Never `Disallow:` a URL you redirect** — that blocks Google from ever seeing the redirect |
| 4 | Head-term pollution across pages | title-prefix grouping | Retarget each page to its own intent; move title+H1+meta+intro together |
| 5 | Near-duplicate hubs (55–58% similar) | pairwise similarity | Differentiate first 100–150 words; cross-link the pair explicitly |
| 6 | Detail clusters at 50–58% | intra-folder similarity | +150 words entity-specific; **first paragraph must be unique** — that's what gets sampled |
| 7 | Orphan pages | body inbound == 0 | Body links from adjacent pages |
| 8 | Deploy gap | `curl -I` vs `ls` | Ship file + compressed siblings; add to sitemap; re-verify |
| 9 | Invisible structured data | schema set − visible set | Add visible UI (doubles as a GEO win) or remove schema |
| 10 | Schema/visible numeric contradiction | value-frequency grep on both | One source of truth, propagated |
| 11 | Stale `lastmod` after an edit pass | distinct `<lastmod>` values | Bump all, resubmit → triggers recrawl |
| 12 | Template-sync script reverting edits | discovered only by regression | Re-verify edits **after** the sync script runs |

Bulk `lastmod` bump (UTF-8, no BOM):

```powershell
$today = (Get-Date).ToString("yyyy-MM-ddTHH:mm:sszzz")
$p = "sitemap.xml"
$t = [System.IO.File]::ReadAllText($p, [System.Text.Encoding]::UTF8)
$t = [regex]::Replace($t, '<lastmod>[^<]+</lastmod>', "<lastmod>$today</lastmod>")
[System.IO.File]::WriteAllText($p, $t, [System.Text.UTF8Encoding]::new($false))
```

**Encoding rule (Windows, non-negotiable):** a prior pass mojibaked 117
files because PowerShell `Get-Content`/`Set-Content` guessed the encoding.
Always `[System.IO.File]::ReadAllText/WriteAllText` with
`[System.Text.UTF8Encoding]::new($false)`, or UTF-8-explicit Python. Sweep
after every bulk pass:

```bash
grep -rl 'â€\|Ã©\|â€™\|Â ' --include='*.html' .    # must return nothing
```

---

## robots.txt pattern

```
User-agent: *
Disallow: /400.html          # every error page (401/403/404/500/502/504)
Disallow: /thin-widget.html
Disallow: /cart/             # functional folders with no SEO value
Disallow: /legacy-cms/       # defensive, if old dirs could leak into deploy
Allow: /
Sitemap: https://www.example.com/sitemap.xml

# AI crawlers — explicit allow
User-agent: GPTBot
Allow: /
User-agent: ClaudeBot
Allow: /
User-agent: PerplexityBot
Allow: /
User-agent: Google-Extended
Allow: /
User-agent: CCBot
Allow: /
User-agent: Applebot
Allow: /
```

**Never `Disallow:` a URL you are also redirecting** — mutually defeating.

---

## GSC remediation loop

1. Deploy.
2. Bump all `lastmod` → resubmit sitemap in GSC **and** Bing Webmaster Tools
   (resubmit even if listed — it forces a fresh fetch).
3. URL Inspection → **Request Indexing**. Quota ≈ **10/day per property** —
   batch by priority.
4. Click **Validate Fix** on each open issue bucket.
5. Monitor Pages daily for 2 weeks; Crawl Stats to confirm budget lands on
   real URLs, not ghosts.

| GSC bucket | Meaning | Action |
|---|---|---|
| Page with redirect | De-duplicating; recorded URL ≠ canonical | Usually harmless — confirm target is 200 |
| Discovered – not indexed (`Last crawled: N/A`) | Discovered, never crawled | Free crawl budget, strengthen internal links, bump lastmod, request indexing |
| Crawled – not indexed | Crawled and **rejected** | Real quality/duplication/signal problem. Check canonicals FIRST, then thin content |

> **Critical inference:** if "Validate fix" *fails* after a content pass, the
> content was never the problem. Return to Stage 3.

Expected timeline — set this before starting, to avoid premature panic:

| Week | Observable |
|---|---|
| 1 | Ghost-URL count drops; recrawl wave begins; new schema detected |
| 2–3 | "Crawled – not indexed" moves to Indexed; rich results appear |
| 3–6 | "Discovered – not indexed" drops sharply |
| 4–8 | New rich-result card types appear |
| 6–12 | Ranking movement on the target keyword set |

**Escalation rule:** if "Discovered – not indexed" is still elevated after 4
weeks, the bottleneck has moved from on-page to **off-page authority**.
Further on-page work has near-zero marginal return — stop optimizing, start
link-building.

---

## Anti-patterns

- ❌ `noindex` on pages that merely *look* thin — forfeits equity. Fix content instead.
- ❌ Deleting non-indexed URLs from the sitemap — removes the discovery signal.
- ❌ 301-ing very long-dead ghosts — 410 purges faster.
- ❌ Rewriting structurally-distinct title groups to satisfy a detector.
- ❌ Trusting an audit finding without a live-vs-local diff.
- ❌ Renaming URL slugs that carry equity, to "improve" them.
- ❌ Blind-insert bulk head-tag scripts.

---

## Checklist

- [ ] Every finding classified: source bug / deploy gap / host / tool artifact
- [ ] Redirect chain from bare http is exactly one hop
- [ ] Ghost URLs return 301 or 410, never 200
- [ ] disk ↔ sitemap ↔ live-200 sets reconciled
- [ ] Exactly one canonical per page (verified with a multi-line-tolerant pattern)
- [ ] Zero unintended `noindex`
- [ ] Exactly one H1 per page
- [ ] Zero duplicate titles / descriptions / H1s
- [ ] Cannibalization groups triaged as real vs structural, with reasons written down
- [ ] Zero orphan pages (body-link graph, nav stripped)
- [ ] Schema-without-visible-content set is empty
- [ ] Zero mojibake after bulk edits
- [ ] Sitemap `lastmod` bumped and resubmitted
