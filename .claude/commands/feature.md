---
description: Take one roadmap item from idea to verified code (spec → plan → TDD → verify)
argument-hint: <roadmap item text or number>
---

Work on this roadmap item: **$ARGUMENTS**

1. Read `CLAUDE.md`, `docs/roadmap/ROADMAP.md` and the relevant files in `docs/architecture/`.
   Find the item and mark it `[~]` (in progress).
2. Use the `brainstorming` skill to write `docs/specs/<date>-<topic>-design.md`.
   **Stop and ask the human to approve the spec.**
3. Use `writing-plans` to write `docs/plans/<date>-<feature>.md` and link spec + plan from the roadmap item.
4. Use `executing-plans` with `test-driven-development` for each step. Use Boost
   `search-docs` before using any Laravel/Inertia API you're unsure of.
   For UI, follow `frontend-design`, `emil-design-eng` and `docs/design/design-system.md`.
5. Use `verification-before-completion`: run `composer ci:check` and screenshot
   any UI change with the playwright MCP.
6. Mark the item `[x]`, add a changelog row, and summarise what changed and what's next.
