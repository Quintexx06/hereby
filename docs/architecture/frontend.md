# Frontend architecture

Vue 3 `<script setup lang="ts">` + Inertia v3 + Tailwind v4 + shadcn-vue.

## Folders (`resources/js`)

| Path                      | Responsibility                                                       |
| ------------------------- | -------------------------------------------------------------------- |
| `pages/`                  | Inertia pages. **Compose** sections/components and hold no big markup. Mirror the controller domain (`pages/declarations/Show.vue`). |
| `layouts/`                | Page shells (`AppLayout`, `AuthLayout`, `MarketingLayout`), chosen in `app.ts`. |
| `components/ui/`          | shadcn-vue primitives. Restyle them through tokens; edit only if needed. |
| `components/brand/`       | Logo, wordmark, wax seal, signature: brand atoms.                    |
| `components/marketing/`   | Landing-page sections.                                               |
| `components/header/`      | App header parts.                                                    |
| `components/<domain>/`    | Feature components (e.g. `components/declarations/StatusBadge.vue`). |
| `composables/`            | Reusable stateful logic (`useX`). Motion helpers live in `composables/motion/`. |
| `directives/`             | Global directives (`v-focus`, `v-reveal`), registered in `directives/index.ts`. |
| `config/`                 | Static app config, e.g. `navigation.ts`, the one place nav items are defined. |
| `content/`                | Marketing copy kept out of components.                               |
| `lib/`                    | Pure helpers (`utils.ts`, `motion.ts`, `gsap.ts`).                   |
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

## Styling rules

1. Use semantic tokens (`bg-card`, `text-muted-foreground`, `border-rule`,
   `text-seal`), never `neutral-*`, `black`/`white` or hex values.
2. Keep utilities in the template when they're short and used once.
3. When a utility group repeats or passes ~8 classes, add a named class with
   `@apply` in `resources/css/components/<area>.css` (`@layer components`).
   If it needs variants (`hover:`, `md:`), use `@utility` in `utilities.css`.
4. Typography classes: `display-xl`, `display-lg`, `display-italic`, `lede`,
   `eyebrow` and `fine-print` (in `base/typography.css`).
5. Layout classes: `page-container`, `section`, `rule` and `surface`.

## CSS map (`resources/css`)

```
app.css               entry, imports only
theme/palette.css     raw brand palette (ink, paper, seal, brass, sage)
theme/semantic.css    light/dark semantic tokens (the shadcn contract + extras)
theme/tailwind.css    @theme inline mapping, font families, radii
theme/motion.css      easing tokens, keyframes, reduced motion
base/                 element defaults and the type scale
components/           @apply component classes, grouped by area
utilities.css         @utility custom utilities
```
