# Frontend architecture

Vue 3 `<script setup lang="ts">` + Inertia v3 + Tailwind v4 + shadcn-vue.

## Folders (`resources/js`)

| Path                      | Responsibility                                                       |
| ------------------------- | -------------------------------------------------------------------- |
| `pages/`                  | Inertia pages. **Compose** sections/components and hold no big markup. Mirror the controller domain (`pages/declarations/Show.vue`). |
| `layouts/`                | Page shells (`AppLayout`, `AuthLayout`, `MarketingLayout`), chosen in `app.ts`. |
| `components/ui/`          | shadcn-vue primitives. Restyle them through tokens; edit only if needed. |
| `components/brand/`       | Logo mark and wordmark: brand atoms.                                 |
| `components/marketing/`   | Landing-page sections.                                               |
| `components/header/`      | App header parts.                                                    |
| `components/invitation/`  | Guest-site parts (`WeddingThemeScope`, hero, events…). Translated, themed and light. |
| `components/<domain>/`    | Feature components (e.g. `components/declarations/StatusBadge.vue`). |
| `composables/`            | Reusable stateful logic (`useX`). Motion helpers live in `composables/motion/`. |
| `directives/`             | Global directives (`v-focus`, `v-reveal`), registered in `directives/index.ts`. |
| `config/`                 | Static app config, e.g. `navigation.ts`, the one place nav items are defined. |
| `content/`                | Marketing copy kept out of components.                               |
| `lib/`                    | Pure helpers (`utils.ts`, `motion.ts`, `gsap.ts`, `format.ts` for dates in venue time). |
| `types/`                  | Shared TypeScript types.                                             |
| `actions/`, `routes/`, `wayfinder/` | **Generated** by Wayfinder. Never edit; never hardcode URLs. |

## Component rules

- **Size:** aim for under ~150 lines per SFC. When a template has more than one
  visual "region", extract it.
- **Props down, events up.** Type props with `defineProps<{...}>()`; use
  reactive destructuring with defaults.
- **One source of truth.** Shared lists (nav, options) go in `config/`, and copy in `content/`.
- **Forms:** use Inertia `<Form>` with Wayfinder `.form()` variants; show
  errors with `InputError`.
- **Icons:** `@lucide/vue`.
- **Copy on guest pages** always goes through `useTrans().t()`. Format dates with `lib/format.ts`.
- **Prop types** for server data live in `types/wedding.ts` and mirror the PHP resources.
- **Performance (rule 2):** guest pages must not import GSAP or heavy libraries
  unless the feature needs them. Check the chunk sizes in `npm run build`.

## Styling rules

1. Use semantic tokens (`bg-card`, `text-muted-foreground`, `border-rule`,
   `text-brand`, `bg-brand`), never `neutral-*`, `black`/`white` or hex values.
2. Keep utilities in the template when they're short and used once.
3. When a utility group repeats or passes ~8 classes, add a named class with
   `@apply` in `resources/css/components/<area>.css` (`@layer components`).
   If it needs variants (`hover:`, `md:`), use `@utility` in `utilities.css`.
4. Typography classes: `display-hero`, `display`, `headline`, `title`,
   `figure`, `accent`, `lede`, `body-copy`, `caption` (in `base/typography.css`).
   Brand: `wordmark`, `link-underline`; dark sections: `.stage`. See `DESIGN.md`.
6. Landing sections live in `components/marketing/`, copy in `content/landing.ts`,
   photos in `content/landing-photos.ts`, 3D in `lib/three/` (dynamic import only).
5. Layout classes: `page-container`, `section`, `rule` and `surface`.

## CSS map (`resources/css`)

```
app.css               entry, imports only
theme/palette.css     raw brand palette (porcelain, night, sand, blush, moss, ember)
theme/semantic.css    light/dark semantic tokens (the shadcn contract + extras)
theme/tailwind.css    @theme inline mapping, font families, radii
theme/motion.css      easing tokens, keyframes, reduced motion
base/                 element defaults and the type scale
components/           @apply component classes, grouped by area
utilities.css         @utility custom utilities
```
