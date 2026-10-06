# Design system — "ink, paper & seal"

See [ADR 0002](../decisions/0002-design-identity.md) for the reasoning.

## Colour

| Token (utility)              | Light          | Dark            | Use                                 |
| ---------------------------- | -------------- | --------------- | ----------------------------------- |
| `background`                 | paper-100      | ink-950         | Page                                |
| `card` / `popover`           | paper-50       | ink-900         | Raised surfaces                     |
| `foreground`                 | ink-900        | paper-100       | Body text                           |
| `muted-foreground`           | ink-500        | ink-300         | Secondary text                      |
| `primary`                    | ink-900        | paper-100       | Primary buttons ("ink on paper")    |
| `seal`                       | seal-600       | seal-400        | **One** brand accent per view, links underline, focus ring |
| `highlight`                  | brass-500      | brass-400       | Rare emphasis (badges, stars)       |
| `success`                    | sage-600       | sage-400        | "Kept" states                       |
| `destructive`                | crimson        | crimson         | Danger only, never decoration       |
| `rule`                       | ink @ 12%      | paper @ 12%     | Hairlines, like a legal pad         |

Raw scales (`ink-*`, `paper-*`, `seal-*`, `brass-*`, `sage-*`) exist for brand
illustration and marketing. Don't use them in app UI.

## Type

| Family                 | Utility        | Use                                          |
| ---------------------- | -------------- | -------------------------------------------- |
| Fraunces Variable      | `font-display` | h1–h3, display sizes, the italic accent word |
| Schibsted Grotesk      | `font-sans`    | Everything else                              |
| Martian Mono           | `font-mono`    | Eyebrows, fine print, numbers, § markers     |

Scale classes: `display-xl` (hero), `display-lg` (section), `lede`, `eyebrow`
and `fine-print`. Display headings are light weight with tight tracking;
`display-italic` turns on Fraunces' WONK axis for the single accent phrase.

## Shape & space

- Radius `0.375rem`: documents are crisp, not bubbly.
- Use `page-container` for width and `section` for vertical rhythm.
- Prefer hairline rules (`rule`, `border-rule`) and whitespace over boxes and shadows.

## Motion

Tokens: `ease-out`, `ease-in-out` and `ease-drawer` (CSS), mirrored as
`hereby.out` / `hereby.inOut` / `hereby.drawer` in GSAP.
Durations: fast 160ms, base 220ms, slow 320ms; hero choreography ≤ 1.5s total.

- Hover/press: CSS transitions, 150–200ms, `ease-out`.
- Scroll reveal: `v-reveal` plus the `reveal` class, staggered with `v-reveal="index * 90"`.
- Signature moments: GSAP via `useGsap()`. Examples are the hero words rising
  (SplitText), the signature drawing (DrawSVG) and the seal stamping.
- Every animation has a reduced-motion fallback that shows the final state.
- Before shipping motion, run the `review-animations` skill.

## Wedding themes (guest sites)

Guest pages don't use the Hereby palette directly. They render inside
`WeddingThemeScope`, which applies a couple's theme by re-declaring the same
semantic tokens (ADR 0004). Build guest UI with semantic tokens only, and it
will follow every theme.

| Theme   | Mood                              | Accent (`seal`) |
| ------- | --------------------------------- | --------------- |
| Ivory   | Hereby house style: paper and ink | Vermilion       |
| Alpine  | Glacier white, slate              | Pine            |
| Riviera | Limestone, espresso               | Terracotta      |

These are starter themes until the designer's collection lands (roadmap Phase 0/1).

## Inspiration sources

- **21st.dev** (Magic MCP) for component ideas; restyle them to our tokens
  before merging.
- **MotionSites** (MCP) for landing-page layout and motion prompts.
- **Emil Kowalski** (skills) for animation craft.
- shadcn-vue MCP for the correct component API.
