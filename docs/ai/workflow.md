# AI workflow

How Claude (or any agent) and humans build Hereby together. The goal is
**small, verified steps that always trace back to the roadmap.**

## The loop

```
ROADMAP item
   │  /feature <item>
   ▼
1. brainstorming          → docs/specs/YYYY-MM-DD-<topic>-design.md   (human approves)
2. writing-plans          → docs/plans/YYYY-MM-DD-<feature>.md        (small TDD steps)
3. executing-plans        → test-driven-development per step
                            + Boost MCP (docs, schema, tinker)
                            + domain skills (inertia-vue, wayfinder, tailwind, fortify)
4. UI work                → DESIGN.md + impeccable (craft floor) + frontend-design
                            + emil-design-eng / animate for motion
                            + shadcn-vue MCP, MotionSites for ideas
                            (the after-edit hook flags design-rule violations)
5. verification-before-completion → composer ci:check, screenshot via playwright MCP
6. requesting-code-review → /code-review, /design-review (impeccable critique +
                            web-design-guidelines), review-animations for motion
7. Tick the roadmap item + changelog row, then /ship
```

Skip steps 1–2 only for fixes that touch a single file or ~20 lines.
When something breaks, use `systematic-debugging`; never guess-and-retry.

## Rules for agents

- Read `CLAUDE.md` → roadmap → the relevant architecture doc before coding.
- Search the Laravel/Inertia/Tailwind docs with Boost `search-docs` (version
  aware) or Context7 instead of relying on memory.
- Generate with artisan (`make:model -mfs`, `make:request`, `make:policy`…).
- Keep files small and reuse existing components. See the non-negotiables in `CLAUDE.md`.
- Never claim "done" without pasting the passing check output.
- Write an ADR for any new dependency, font, colour or architectural pattern.

## Session hygiene

- One roadmap item per branch or PR.
- Commit after each green plan step; use imperative commit subjects.
- Keep `docs/` current. Docs that drift from the code are worse than no docs.
