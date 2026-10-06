# Hereby — agent guide

Hereby is a Laravel 13 + Inertia v3 + Vue 3 app (shadcn-vue, Tailwind v4).
Its brand idea is the formal act of declaring something: **"I hereby…"**:
ink, paper and a wax seal. Read this file first, then follow the links.

@AGENTS.md

## Where things are

| Need                         | Read                                                       |
| ---------------------------- | ---------------------------------------------------------- |
| What we're building and when | [docs/roadmap/ROADMAP.md](docs/roadmap/ROADMAP.md)         |
| How to work (AI workflow)    | [docs/ai/workflow.md](docs/ai/workflow.md)                 |
| Which skill / MCP to use     | [docs/ai/skills.md](docs/ai/skills.md), [docs/ai/mcp.md](docs/ai/mcp.md) |
| Backend structure            | [docs/architecture/backend.md](docs/architecture/backend.md) |
| Frontend structure           | [docs/architecture/frontend.md](docs/architecture/frontend.md) |
| Colours, type, motion        | [docs/design/design-system.md](docs/design/design-system.md) |
| Why a decision was made      | [docs/decisions/](docs/decisions/)                         |

## Non-negotiables

1. **Roadmap is the source of truth.** Work on a roadmap item, and tick it off
   in `docs/roadmap/ROADMAP.md` in the same PR that finishes it.
2. **Spec → plan → TDD → verify.** Anything bigger than a small fix goes through
   the `brainstorming` → `writing-plans` → `executing-plans` skills. Specs live in
   `docs/specs/`, plans in `docs/plans/`.
3. **No long files.** Vue SFCs stay under ~150 lines and PHP classes under ~200.
   Split by responsibility before you reach that size.
4. **Reuse before you write.** Check `components/ui`, `components/brand`,
   `composables/`, `app/Actions` and the CSS component classes first.
5. **Tailwind discipline.** Use semantic tokens (`bg-background`, `text-seal`),
   never raw hex/neutral values. If the same group of utilities appears more
   than twice, or a class list gets longer than ~8 utilities, move it into a
   named class with `@apply` in `resources/css/components/*.css`.
6. **Brand fonts and colours only.** Use `font-display`, `font-sans` and
   `font-mono` with the ink/paper/seal tokens. Don't add a font or palette
   without writing an ADR.
7. **Motion with intent.** Follow the `emil-design-eng` skill: enter with
   ease-out, keep UI motion under 300ms, never start from `scale(0)`, and
   respect reduced motion. Import GSAP only from `@/lib/gsap`.
8. **Verify before claiming done:** `composer ci:check` (runs `vp check`,
   `vue-tsc`, Pint, PHPStan and PHPUnit).

## Commands

```bash
composer dev          # server + queue + logs + vite
composer ci:check     # everything CI runs
php artisan test --compact --filter=Name
npm run check:fix     # format + lint frontend
vendor/bin/pint --dirty
```

## Slash commands

- `/feature <roadmap item>`: run the full workflow for one roadmap item
- `/roadmap`: show roadmap status and propose the next item
- `/design-review <path>`: check UI against the design system and motion rules
- `/ship`: run the pre-push checks and write the commit message
