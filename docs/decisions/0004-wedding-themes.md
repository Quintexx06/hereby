# 0004: Per-wedding themes on guest sites

**Status:** Accepted (2026-10-06)

## Context
"Design is the product." Each couple's site needs its own look, chosen from a
designer-curated set, while the platform (marketing, dashboard) keeps the
Hereby identity. A fully open editor is explicitly out of scope.

## Decision
- A `WeddingTheme` enum on `weddings` names a curated theme.
- Each theme is a `[data-theme='…']` block in `resources/css/themes/wedding.css`
  that re-declares the **semantic tokens** (colours, `--type-display`,
  `--type-sans`). `WeddingThemeScope.vue` applies it to the guest page.
- Font families are tokens (`--type-*`), so a theme can bring its own type pairing.
- Guest sites are always rendered light (`color-scheme: light`), whatever the
  visitor's OS setting.
- Starter themes: Ivory (house), Alpine (mountain and lake), Riviera (Ticino).
  The designer's collection replaces them in Phase 0/1.

## Consequences
- Components never hardcode colours, so every shadcn component follows the theme for free.
- Portalled overlays (dialogs, popovers) render outside the scope. Before guest
  sites use them, portal into the scope element or re-apply `data-theme` on the portal.
- Adding a theme takes a CSS block, an enum case and a contrast check (WCAG AA).
