---
description: Pre-push checks and a conventional commit message
---

1. Run `vendor/bin/pint --dirty`, then `npm run check:fix`.
2. Run `composer ci:check` and paste the summary. If anything fails, stop and fix it
   (use `systematic-debugging`).
3. Check that `docs/roadmap/ROADMAP.md` reflects the work (status + changelog).
4. Show `git status` and `git diff --stat`, then propose a commit message:
   an imperative subject of 72 characters or fewer, and a body that explains why.
5. Commit only after the human confirms.
