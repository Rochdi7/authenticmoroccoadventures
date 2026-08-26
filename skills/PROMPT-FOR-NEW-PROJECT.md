# Prompt to paste into Claude on the new project

**How to use this file:**

1. Copy the whole `skills/` folder into the Authentic Morocco Adventures
   project root.
2. Open Claude Code in that project.
3. Paste everything between the `====` lines below as your first message.

---

====================== COPY FROM HERE ======================

# SEO OPTIMIZATION PASS — Authentic Morocco Adventures

## Your skills

I have pasted a `skills/` folder into this project root. It contains 11 SEO
skills distilled from a full production SEO programme on a comparable
Morocco tour-operator site. **Read them and follow them — do not improvise
your own SEO method.**

Read in this order:

1. `skills/README.md` — index + the 7 cross-cutting rules. Read fully.
2. `skills/seo-project-adapter.md` — **read before touching anything.**
   Detect this project's stack (static HTML / Laravel / WordPress / Next.js)
   and follow its instructions for getting an auditable rendered corpus.
3. Then load each skill as its phase comes up:
   - `seo-verification.md` — verifying findings
   - `seo-technical-audit.md` — crawl/index/canonical/redirects
   - `seo-cannibalization.md` — duplicate + competing + thin pages
   - `seo-keyword-research.md` — per-page targeting, titles/meta
   - `seo-internal-linking.md` — orphans, link graph, related blocks
   - `seo-schema.md` — JSON-LD
   - `seo-geo-ai-search.md` — AI/ChatGPT/Perplexity citability
   - `seo-content-strategy.md` — content gaps
   - `seo-performance.md` — Core Web Vitals
   - `seo-reporting.md` — writing the final report

## Working style — CAVEMAN MODE while you work

While doing the audit and the fixes, work in caveman mode:

- Speak short. Very short. No long explanation. No theory. No repeat.
- Action first. Think → do → move.
- If unsure → choose best → continue. Do not ask many questions.
- Report progress ONLY in this format:

```
NOW: <what you doing>
DONE: <what finished>
BLOCKED: <real blocker or none>
NEXT: <next action>
```

**IMPORTANT EXCEPTION — the client report is NOT caveman.**
Caveman mode applies to your chat messages to me, to save tokens.
The final `.md` report is going to a paying client and must be written in
full, professional, plain-English prose. Never write the client report in
caveman style. See "Deliverable" below.

## Order of work

Follow `skills/README.md` "Recommended order for a full site pass":

```
0. seo-project-adapter   ← unless plain static HTML
1. seo-verification      ← only if acting on an existing audit
2. seo-technical-audit   ← structure before content, always
3. seo-cannibalization
4. seo-keyword-research
5. seo-internal-linking
6. seo-schema
7. seo-geo-ai-search
8. seo-content-strategy
9. seo-performance
10. seo-reporting
```

Steps 2 and 3 are load-bearing. Do not do content work before they are clean.

## Hard rules — do not break these

1. **NEVER fabricate data.** No invented review counts, ratings, prices,
   coordinates, addresses, credentials, or testimonials. If real data is
   missing, OMIT the property and put it in the report as "needs real data
   from client". This is the #1 rule.
2. **Schema must match what is visible on the page.** No FAQ schema without
   a visible FAQ block.
3. **Verify before you fix.** Re-measure every finding with a different
   method before acting. Multi-line `<link>` tags break naive greps.
4. **Never rename URL slugs.** They carry SEO equity, even if misspelled.
5. **Never delete pages** without asking me first.
6. **Do not change design, CSS, or layout.** SEO only.
7. **Commit before any bulk edit.** `git checkout` is the undo.
8. **Escalate, do not decide:** page merges, redirects, canonical choices,
   and nav removal need MY approval. Put them in the report as
   recommendations.
9. **UTF-8 safe edits only.** Never PowerShell `Get-Content`/`Set-Content`.
10. If the site is dynamic: **audit rendered output, fix the template.** One
    broken template = ONE finding with an affected-URL count.

## What I want from you

**A. Do the audit.** Work through the phases. Fix what is safe and mechanical
(missing canonicals, missing alt text, broken schema, missing image
dimensions, thin metadata, internal-link gaps).

**B. Flag what you cannot fix.** Anything needing hosting access, real
business data, or an architecture decision goes in the report, not silently
dropped.

**C. Write the client report** (see below).

## Deliverable — the client report

Write it to `SEO-REPORT-AUTHENTIC-MOROCCO-ADVENTURES.md` in the project root.

Follow `skills/seo-reporting.md`, and use the **client report register**
(§1 of that skill): lead with the executive summary and the score, and gloss
every technical term in plain English. The client is a tour operator, not an
engineer. If you write "canonical tag", explain in one clause what it does.

Required sections:

1. **Executive summary** — 1 short paragraph, plain English, no jargon.
2. **SEO health score** — weighted table, category / weight / before / after,
   with an overall number. Use the weights in `seo-reporting.md` §3.
3. **Top 5 critical issues** and **Top 5 quick wins**, separated.
4. **What was already correct** — a table of checks that PASSED. Do not skip
   this section; it proves you actually looked.
5. **What I fixed** — grouped by category, with before → after counts.
6. **What still needs doing, and why it is not done** — a table with a
   "what's needed / who must do it" column. Three valid reasons: needs
   server/hosting access · needs real data from the client (never fabricate)
   · would be a regression.
7. **30-day action plan** — bucketed by week.
8. **Final validation numbers** — re-measured on the FINAL state of the
   files, not predicted.
9. **Appendix: limitations** — name every data source you did NOT have
   (Google Search Console, PageSpeed API, Semrush, backlink data) so
   estimated figures are never mistaken for measured ones.

Report rules:
- Re-measure all final numbers on the finished tree. Never report predicted
  numbers.
- Annotate every non-zero metric. An unexplained non-zero looks like a bug;
  an explained one is a finding.
- Report your own mistakes if you make any. It builds trust.
- No fabricated numbers anywhere in the report either.

## Start here

1. Read `skills/README.md` and `skills/seo-project-adapter.md`.
2. Detect the stack and tell me what it is.
3. Tell me if the site is live and give me the URL you will audit against,
   or tell me you are auditing local files only.
4. Then start Phase 2 and report in NOW/DONE/BLOCKED/NEXT format.

Go.

======================= COPY TO HERE ========================

---

## Notes for you (not for Claude)

**About the caveman conflict.** Caveman mode and a client-facing report pull
in opposite directions — caveman says "no long explanation", a client report
is *entirely* explanation. The prompt above resolves this explicitly: caveman
for chat, full prose for the deliverable. If you drop that carve-out, you will
get a terse, unsendable report.

**Token saving.** The real cost on a job like this is the audit loop, not the
report. Caveman mode on chat messages is where the savings are. Two further
levers if the project is large:

- Tell Claude to sample by template rather than crawl every page (see
  `seo-project-adapter.md` §3 Option B).
- Have it batch its NOW/DONE reports per phase instead of per file.

**If the site is live**, give Claude the real URL. Several of the most
valuable checks (`curl -I` for redirects, compression, staging `noindex`
leaks, soft 404s) only work against a running site, and the skills lean on
them heavily.

**If it is a Laravel or WordPress site**, do not skip
`seo-project-adapter.md`. Without it Claude will grep templates, find
nothing, and report a clean site that is not clean.
