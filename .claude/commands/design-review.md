---
description: Review UI code against the Hereby design system and motion rules
argument-hint: [path or blank for current diff]
---

Review ${ARGUMENTS:-the current git diff} against `DESIGN.md`,
`docs/architecture/frontend.md` and `CLAUDE.md`. Use the `impeccable` skill
(`critique`, then `audit`) and the `web-design-guidelines` skill for the checklist.

Check for:
- raw colours (`neutral-*`, `black`, `white`, hex) instead of semantic tokens
- long utility chains (over ~8 classes) or repeated groups that should be an `@apply` class
- fonts other than Archivo, italics, eyebrow/kicker labels, monospace as decoration,
  cream or beige grounds, gradients or glows (see DESIGN.md "Do's and Don'ts")
- gentian (`brand`) used as a tint or scattered accent instead of a field or a precise detail
- SFCs over ~150 lines, duplicated markup, and lists or copy that belong in `config/` or `content/`
- motion: use the `review-animations` skill (easing, duration, reduced motion, `useGsap` cleanup)
- accessibility: focus states, labels, contrast, keyboard paths

Output a table: **File:line | Issue | Fix**. Then offer to apply the fixes.
