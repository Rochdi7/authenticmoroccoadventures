---
name: seo-performance
description: >
  Take a page from a mediocre PageSpeed score to 90+ without changing how it
  looks. Font self-hosting, the css-loading transition freeze, JS enhancer
  gating, LCP preload matching, CLS root-causing, and the nginx
  Accept-Encoding-stripping gotcha that no repo-level check can detect.
  Includes the visual-regression gate that proves "zero design change".
---

# SEO Performance Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

Battle-tested: **mobile 75 → 97 in one day, zero visual diff.**

---

## 0. Read the score like an engineer

Lighthouse mobile weights: **TBT 30% · LCP 25% · CLS 25% · FCP 10% · SI 10%.**

Before touching anything, compute where the missing points actually are. A 75
with TBT 0ms and CLS 0.01 is an FCP/LCP problem (network); a 75 with CLS 0.25
is ~6 points of layout instability. **Fix in order of points, not in report
order.**

The "Diagnostics" section (unused CSS/JS, forced reflow, DOM size, image
savings on lazy below-fold images) is **unscored** — never spend risk budget
there while scored metrics are red.

Two traps in every audit:

- **The report is a snapshot.** Check `Last-Modified` on live files vs the
  report timestamp — deploys happen mid-conversation.
- **Lab ≠ your machine.** PSI runs headless Chrome on **Linux** (no Arial, no
  Times, no Georgia — `local()` font fallbacks silently dead) with Slow-4G +
  4× CPU throttle. Fast local loads hide every CSS/JS race.

---

## 1. Verify what the server actually sends — before editing anything

```bash
D=https://SITE
curl -sI -H "Accept-Encoding: br, gzip" $D/                      # doc compressed?
curl -sI -H "Accept-Encoding: br, gzip" $D/assets/css/app.css    # assets compressed?
curl -sI $D/index.html.br                                        # siblings deployed?
curl -sI $D/index.html                                           # rewrites alive? (expect 301)
curl -sI $D/ | grep -i '^server:'                                # what stack is in front?
```

### The compression gotcha — the single largest lever

**Symptom:** everything served uncompressed despite a complete Brotli/gzip
config and `.br`/`.gz` siblings sitting on the server.

**The discriminating test set:**

```
GET /                + Accept-Encoding: br,gzip  → 200, NO Content-Encoding, 228,651 B
GET /index.html.br   (direct)                    → 200, Content-Encoding: br, 25,932 B  ✓
GET /index.html      → 301 /                     ✓ mod_rewrite runs
Server: nginx
```

**Diagnosis:** rewrite rules fire, header rules fire, `.br` files exist and
serve correctly by name — but *every rule conditioned on
`%{HTTP:Accept-Encoding}` never fires*. Therefore the front nginx proxy is
**stripping the `Accept-Encoding` request header** before it reaches Apache,
and nginx's own gzip is off.

> **No `.htaccess` change can fix this — the header never arrives.**
> `Vary: Accept-Encoding` in the response is a red herring: it means
> negotiation is *configured*, not that it *works*.

**Fixes, in order of preference:**

1. **CDN in front (Cloudflare free tier)** — automatic Brotli, edge caching
   near PSI test locations, HTTP/3, and it forwards `Accept-Encoding` to
   origin so existing sibling-serving starts working. ~15 min, nameserver
   change, no code.
2. **Host support ticket**, naming the exact mechanism: *"your nginx reverse
   proxy strips the `Accept-Encoding` request header and nginx's own gzip is
   off; either enable gzip/brotli at the nginx layer for text/html, text/css,
   application/javascript, or pass the header through unchanged."*
3. **Interim UA-based serving** — nginx strips `Accept-Encoding` but passes
   `User-Agent`, and `Chrome-Lighthouse`, Googlebot and Bingbot all
   guarantee Brotli. Serve the existing `.br` siblings **to those UAs only**,
   with `Vary: User-Agent` so no cache hands a `br` body to a normal browser.
   This took the reference score 88 → 97 and genuinely speeds crawling — but
   it is **not a fix for real visitors** and must be labeled for removal.

Honest framing for the client: *"the audit now measures the true compressed
experience; human visitors still need the infra step."*

**Cost, quantified:** at PSI's 1.6 Mbps throttle, a 228 KB raw document costs
~1.1 s of download alone; ~1.5–2.5 s of FCP/LCP/SI overall. Ceiling without
the infra fix: mobile ≈ 85–92. With it: 95–100. **Name the ceiling** rather
than grinding on the repo.

Acceptance tests:

```bash
curl -sI -A "Chrome-Lighthouse" $D/ | grep -i 'content-'          # interim
curl -sI -H "Accept-Encoding: br" $D/ | grep -i content-encoding  # the REAL fix
```

---

## 2. Fonts

- **Self-host instead of Google Fonts** — the third-party chain measured
  ~2.4 s on throttled mobile. Removing it moved FCP 3.8 → 2.2 s.
- **Download the exact files Google serves**, not a re-export — byte-identical
  rendering, no visual delta to defend.
- **Subset to what the content uses** (latin + latin-ext; the other 5 subsets
  were dead weight).
- **Mirror Google's `@font-face` declarations 1:1** — per-weight declarations,
  same `unicode-range`s, `font-display:swap`. Declaration *order* matters:
  declare normal-400 last so it keeps owning weight 400.
- `<link rel="preload" as="font" type="font/woff2" crossorigin>` for **only
  the one or two faces with the largest above-the-fold coverage.** One
  late-preloaded face was a 2,475 ms tail on the critical chain.
- **Metric-calibrated fallback stacks** (`size-adjust`/`ascent-override`) must
  live in the **main stylesheet**, not only in critical inline CSS — otherwise
  the async stylesheet *replaces* the calibrated fallback mid-load and causes
  a second reflow.
  - `local("Arial")`-based calibration **silently no-ops on Linux PSI
    runners**. Measured impact small (~0.001 CLS), but don't count on it.

---

## 3. CSS/JS loading

### The `css-loading` transition freeze

When an async stylesheet lands, elements gain `transition` rules *in the same
style recalc* that changes their computed values — per spec the browser
animates the delta. Lighthouse reports dozens of "non-composited animations"
plus layout shifts.

```html
<html class="css-loading">
<style>html.css-loading *{transition:none!important}</style>
```

Remove the class **two `requestAnimationFrame`s after the stylesheet's
`onload`**, with a ~4 s failsafe timer. Nothing animates at load; every
genuine hover/menu transition is untouched.

### Gate markup-injecting JS enhancers on CSS readiness

This caused a **CLS 0.269 regression**. A deferred script wrapped native
`<select>`s in custom dropdown markup at DOMContentLoaded, but the async
stylesheet arrived seconds later (because uncompressed), so the injected
button and option list rendered **unstyled and in flow** — then snapped into
place. Two shifts of 0.148 + 0.119.

Fix: a `whenCssReady()` helper deferring every markup-injecting enhancer until
`html.css-loading` is removed. Native controls stay functional meanwhile.

> **Diagnostic signature to memorize: duplicated text in the CLS culprit
> string = an unstyled JS-injected component.**

It only reproduces under throttling. On a fast local load the CSS wins the
race — which is exactly why it shipped.

### Other

- Replace every `transition: all` with an explicit property list. Identical
  end state, but inherited layout properties can never be incidentally
  animated.
- **Deliberately not done: purging unused CSS/JS.** Both load async/deferred,
  so the unused bytes gate no paint metric and the score gain was ~0. **Not
  every Lighthouse diagnostic is worth points** — check whether the resource
  is even on the critical path first.

---

## 4. LCP and CLS

### LCP delivery

- **Preload format must match what the page serves.** A `<picture>` serving
  AVIF while the head preloaded WEBP → double download plus 2 s+ resource
  load delay. Preload the AVIF `srcset` with `type="image/avif"`.
- **Remove `fetchpriority="high"` from anything that isn't the LCP element** —
  a high-priority logo competes with the hero.

### CLS from images

- **Aspect-ratio mismatch between `width`/`height` attributes and the actual
  file** reserves the wrong box and snaps on decode (a 150×37 attribute pair
  on a 3.0-ratio file rendered at `height:40px;width:auto` → 42 px snap).
  Verify attributes against real file dimensions.
- Every `<img>` needs `width`/`height` (or `aspect-ratio`) and an explicit
  `loading` attribute.

```bash
grep -rn '<img' --include='*.html' . | grep -v 'width=' | wc -l
grep -rn '<img' --include='*.html' . | grep -v 'loading=' | wc -l
```

⚠️ **Do not blanket-add `loading="lazy"`.** Above-the-fold images (logos,
hero) must stay eager — absent `loading` *is* correct there. Adding lazy
regresses LCP. See `seo-verification.md` §4.

### Images (bytes)

- Regenerate oversized variants **from the pristine original**, not from an
  already-encoded derivative — double-encoding inflates size and degrades
  quality (`libwebp q65 m6` worked).
- **Check each responsive variant is actually smaller than the source.** A
  700w variant was *larger* (92 KB) than the 736w original (60 KB).
- **Document the quality floor** so nobody re-attempts it. PSI's "potential
  savings" estimate is not always achievable.
- Below-fold + lazy images are **diagnostics, not score** — deprioritize.

---

## 5. JS main-thread

- **Forced reflow:** map Lighthouse's flag back through minified offsets to
  the specific reads. On the reference project: an initial `window.scrollY`
  read at DOMContentLoaded (26 ms) and a carousel's initial
  `getBoundingClientRect`/`getComputedStyle` right after a class pass had
  dirtied styles (5 ms). Fix: run both inside a **double
  `requestAnimationFrame`** so layout is clean and the reads are free.
- Guard re-entrancy when deferring init so late init is a supported state.
- Disable autoplay/animation under Lighthouse/webdriver detection.
- Batch reads/writes; cache measurements; defer non-essential effects to idle.

---

## 6. Minification and pre-compressed siblings

- `npx esbuild --minify` for CSS and JS; `node --check` the minified JS as a
  syntax gate.
- Regenerate `.br` (brotli q11) + `.gz` (level 9) for every changed text
  asset, and **verify decompressed output is byte-identical to source.**

> **Build-order gotcha:** a re-minify that predates the last source edit ships
> a `.min` file missing the fix. **Always re-minify after the final source
> edit**, then regenerate compressed siblings from the new minified file.

If pages load `.min` files, every source edit must be mirrored into the `.min`
file and stale `.gz`/`.br` regenerated — or the edit is invisible in
production.

---

## 7. Visual-regression gate — proving "zero design change"

Because every change was constrained to be visually inert, the claim was
**proven, not asserted**:

- **Playwright, HEAD worktree vs working tree** — full-page screenshots at
  desktop (1350×940) and mobile (412×915), `prefers-reduced-motion` forced.
  Pass criterion: page heights byte-identical, header/nav/footer
  pixel-identical; only permitted diff is re-encoded photo texture noise.
- **CLS instrumentation under exact audit conditions** — Slow-4G + 4× CPU
  throttle with a `PerformanceObserver` on `layout-shift`, attributing shifts
  to specific selectors.
- **A/B isolation to disprove a hypothesis** — a forced Arial↔Inter swap
  measured 0.00 px difference, ruling out font metrics and forcing the search
  for the real cause.

> **Don't fix the plausible cause — disprove it first.**

- Functional smoke checks after every change (dropdowns, carousel, date
  picker, mobile menu).
- Every JSON-LD block re-parsed and confirmed untouched.

---

## 8. Measure after every deploy

A fix can regress a different metric. The reference project ran four measured
passes in one day:

| Run | Score | FCP | LCP | TBT | CLS | Doc |
|---|---|---|---|---|---|---|
| Baseline | 75 | 3.8 s | 4.0 s | 20 ms | 0.089 | 229 KB |
| After fonts | **74** | 2.2 s | 3.3 s | 0 ms | **0.269** ← regression | 229 KB |
| After CLS root cause | 88 | 2.2 s | 3.3 s | 0 ms | ~0 | 229 KB |
| After compression + reflow | **97** | — | — | 0 ms | ~0 | **27 KB** |

**CrUX "No Data"** on low-traffic sites means you are optimizing lab metrics
only. Say so in the report.

---

## 9. Checklist

- [ ] Missing points computed by metric weight before any edit
- [ ] Server compression verified with the 4-request matrix
- [ ] Host stack identified (`Server:` header) before writing rewrite rules
- [ ] Fonts self-hosted, subset, 1:1 declarations, ≤2 preloads
- [ ] `css-loading` transition freeze in place
- [ ] Every markup-injecting enhancer gated on CSS readiness
- [ ] Preload format matches what the page actually serves
- [ ] `fetchpriority="high"` only on the LCP element
- [ ] All images have correct `width`/`height`; above-fold NOT lazy
- [ ] Initial layout reads inside double rAF
- [ ] Re-minified after the final source edit; siblings regenerated and verified
- [ ] Visual regression proven, not asserted
- [ ] Re-measured after every deploy
- [ ] Ceiling named if infra-blocked
