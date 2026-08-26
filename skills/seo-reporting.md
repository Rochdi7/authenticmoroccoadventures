---
name: seo-reporting
description: >
  Structure SEO audits, work logs and client reports so they stay credible.
  Weighted health scoring, the two-register rule (work log vs forward plan vs
  client report), four before/after presentation patterns, and the ten
  reporting rules — including reporting your own mistakes and what was
  already correct.
---

# SEO Reporting Skill

> **Stack note.** Commands below are written for a tree of static HTML files.
> On Laravel/Blade, WordPress, Next.js, Rails, Django or any dynamic stack,
> **read [seo-project-adapter.md](seo-project-adapter.md) first** — it tells
> you how to obtain a rendered corpus so every command here works unchanged,
> and how to trace each finding back to the template that caused it.
> Rule of thumb: **audit rendered output, fix the template.**

A report's job is to be **auditable**, not impressive. Every rule here exists
to make a later reader able to check you.

---

## 1. Pick your register deliberately

Three distinct document types. Don't blend them.

| Doc | Role | Register |
|---|---|---|
| **Work log** | What was edited, file by file, pass by pass | Technical, exhaustive, for the implementer |
| **Forward plan** | What gaps remain, what to do, in what order | Prioritized, for the decision-maker |
| **Client report** | Same facts, plain-language glosses | Leads with executive summary + score |

Work logs lead with **scope and verification**. Client reports lead with the
**executive summary and the score**. A client report glosses every technical
term: *"GEO is optimization for AI search engines. When a user asks ChatGPT
'…', the AI reads hundreds of pages and cites the most reliable ones."*

---

## 2. Report skeleton

```
# {Work type} — {date}
**Site:** · **Scope:** · **Stack:** · **Pages in scope:** {N canonical + M excluded}

## 0. TL;DR
   3–4 numbered sentences. What happened, in order. Include rejected work.
   Ends with a one-line statement of the final state.

## 1. Audit findings
   1.1 What was already correct    ← table of checks that PASSED
   1.2 The actual problem          ← ONE named root cause + the measurement
   1.3–1.5 Supporting findings, each with a before-state measurement
   1.6 Explicit non-findings       ← what you checked and chose NOT to change

## 2. What was implemented
   One subsection per change type. Each carries:
     - the rule/model/weights used
     - before → after counts
     - a sample of the actual output

## 3. {The contentious decision}
   Full sequence table including rejected stages. How it was verified/reverted.

## 4. Bugs found and fixed
   Including PRE-EXISTING bugs (prove it), and self-inflicted errors caught
   and reverted (state them).

## 5. Final validation
   Monospace metric blocks, re-measured on the FINAL state.
   5.1 Technical · 5.2 What you changed · 5.3 Behavioural · 5.4 Deploy artifacts

## 6. Files modified
   Table: file(s) | change. Plus "pre-existing work preserved, untouched".

## 7. Final architecture
   ASCII diagram of the end state, and why each layer is independent.

## 8. Remaining issues / next steps
   Numbered table: # | item | notes. Include out-of-scope and accepted items.

## 9. Reusable notes for future work
   The gotchas. Written for the next person, not the client.
```

**§1.1 "What was already correct"** and **§9 "Reusable notes"** are the two
most under-used sections in typical SEO reports, and the two highest-value.

---

## 3. Scoring

### A. Weighted health score

Per-category table plus one headline number. Weights make it defensible:

| Category | Weight | Before | After |
|---|---|---|---|
| Technical SEO | 22% | 52/100 | 88/100 |
| Content Quality | 23% | 74/100 | 85/100 |
| On-Page SEO | 20% | 68/100 | 80/100 |
| Schema / Structured Data | 10% | 62/100 | 90/100 |
| Performance (CWV) | 10% | 55/100 | 75/100 |
| AI Search Readiness (GEO) | 10% | 66/100 | 86/100 |
| Images | 5% | 80/100 | 97/100 |

Lead with **Top 5 Critical Issues** and **Top 5 Quick Wins**, always
separated. Quick wins are defined as mechanical, scriptable and zero-risk.

### B. Deployment-readiness score

```
Score: 98/100 — READY TO DEPLOY

| Title uniqueness       | 10/10 |
| H1 uniqueness          | 10/10 |
| Meta description unique| 10/10 |
| Canonical coverage     | 10/10 |
| Schema validity        | 10/10 |
| Internal linking       |  9/10 |
| Anti-cannibalization   |  9/10 |
| Image alt coverage     | 10/10 |
| Orphan pages           | 10/10 |
```

Backed by a check table whose result column permits **three** states:
`✅ PASS` · `✅ RESOLVED` · `⚠️ ACCEPTED` (with the reason).

> **The `⚠️ ACCEPTED` state is what keeps the score honest.**

### C. Three-column progress score

Baseline / current / projected, per dimension. Shows movement *and* remaining
headroom in one table.

### Scoring the verification pass

Score per category, with reasoning attached:

```
Keyword optimization    78   sound logic; docked for the unapplied fix
Consistency             68   docked hard for the 11-file stale-schema gap
Schema consistency      60   valid JSON, but a title↔schema mismatch
Cannibalization         85   both cases genuine; one fixed, one escalated
                     ──────
Overall                 75
```

> Note the **highest** score went to the category where the auditor
> **declined to act unilaterally.** Escalating correctly is success, not
> incompleteness.

---

## 4. Four before/after presentation patterns

**1 — Inline arrow columns**, for many small deltas:
```
{page-a}       0 -> 5
{page-b}       1 -> 4
```

**2 — Old/new tables**, for editorial changes:
| Page | Old title | New title |

**3 — Monospace metric block**, for final validation. Dot leaders, aligned
numbers, before-value in a trailing paren:
```
Median in-content inbound links .... 7   (was ~2)
Hubs with 0 contextual inbound ..... 0   (was 20)
Zero-inbound pages ................. 4   (offers/wishlist — robots-Disallowed;
                                          privacy/terms — correctly footer-only)
```

> **An unexplained non-zero is a bug report; an explained non-zero is a
> finding.** Always annotate.

**4 — Sequence table for a contested decision**, including rejected states:
| Stage | Metric A | Outcome |
|---|---|---|
| Original | … | — |
| Attempt 1 | … | Rejected |
| Final | … | Accepted |

---

## 5. The ten credibility rules

1. **Re-measure everything on the final state.** State it: *"All figures were
   re-measured on the final state of the working tree."* Never report mid-pass
   numbers.
2. **Report what was already correct.** A table of passing checks costs
   nothing and proves the failing findings were actually looked for.
3. **Report the checks that concluded "no change needed"**, with reasoning.
4. **Report your own mistakes** — how they were caught, how reverted, and the
   correct approach. This is the strongest trust signal available.
5. **Prove a risky automated step was safe, empirically.** Hash page bodies
   before/after and print `pages whose BODY changed: 0 (of 126)`.
6. **Distinguish source-repo state from production state.** Several "findings"
   are usually deploy gaps. Diff live vs local before reporting.
7. **Separate accepted-as-is items from open bugs**, and justify each in one
   line: *"~5-char overrun, keyword front-loaded. Left as-is; rewriting risked
   weakening copy for no gain."*
8. **List preserved third-party work** you deliberately did not touch —
   prevents the next reader assuming you clobbered it.
9. **Attach expected-outcome forecasts with a timeline**, as ranges, plus a
   monitoring table (signal / where / frequency):
   | Week | Expected |
   |---|---|
   | 1 | sitemap recrawled, new schema detected |
   | 2–3 | rich results appear in GSC Enhancements |
   | 4–8 | position movement on target keywords |
10. **Close with a manual-actions-required section** — what can't be done from
    the repo (deploy, sitemap submission, GSC index requests), as a numbered
    checklist with literal URLs.

---

## 6. Sections that separate good reports from bad

### "Why they remain" table

For everything not fixed, with an explicit owner column:

| Issue | Why it's not fixed here | What's needed |
|---|---|---|
| Compression not served | `.htaccess` is correct in repo; a SAFE-MODE fallback suggests the full config 500'd on the host | Hosting access to confirm which config is live |
| `GeoCoordinates` missing | No real lat/long available; **fabricating one is the exact risk that caused the review-count problem** | Real GBP pin coordinates from the owner |

Three legitimate reasons for non-fix: **needs server/hosting access**,
**needs real data from the client (never fabricate)**, **would be a
regression**.

### Verification limitations appendix

Name every data source that **wasn't** available, so estimates are never
mistaken for measurements:

> No Google Search Console, PageSpeed Insights API, or CrUX field data access
> was available — Core Web Vitals figures are estimated from static HTML
> analysis, not live lab or field data.

### Corrections to a prior report

When verifying someone else's audit (or your own), publish the corrections
table **before** the implementation section. See `seo-verification.md`.

> Reports are living records. **A corrected report beats a correct report plus
> a stale wrong one.** Edit the original in place with a "Post-Publication
> Corrections" section explaining what changed and why.

---

## 7. Checklist

- [ ] Register chosen deliberately (work log / forward plan / client report)
- [ ] TL;DR includes rejected work, not just shipped work
- [ ] "What was already correct" table present
- [ ] Explicit non-findings section present
- [ ] Weighted score with per-category before/after
- [ ] Top 5 Critical and Top 5 Quick Wins separated
- [ ] All final numbers re-measured on the finished tree
- [ ] Every non-zero metric annotated
- [ ] Own mistakes documented with detection and reversal
- [ ] Deploy gaps separated from source defects
- [ ] "Why they remain" table with owner column
- [ ] Limitations appendix naming unavailable data sources
- [ ] Timeline forecast with monitoring cadence
- [ ] Manual-actions checklist with literal URLs
