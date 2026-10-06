# Roadmap

> The single source of truth for what gets built. Agents and humans tick items
> off **in the PR that ships them**. Larger items get a spec in `docs/specs/`
> and a plan in `docs/plans/`; link both from the item.

**Status:** `[x]` done · `[~]` in progress · `[ ]` planned · `[?]` needs product decision

> ⚠️ Phases 1–4 are a **draft** based on the brand idea ("I hereby…": making,
> signing and keeping declarations). Replace them with the real product roadmap
> when it's final. The structure and workflow stay the same.

---

## Phase 0: Foundation ✅

- [x] Laravel 13 + Inertia v3 + Vue 3 starter kit (Fortify auth, 2FA, passkeys, Wayfinder)
- [x] shadcn-vue (reka-ui) component base
- [x] Brand identity: ink / paper / seal palette, Fraunces + Schibsted Grotesk + Martian Mono ([ADR 0002](../decisions/0002-design-identity.md))
- [x] Modular Tailwind v4 theme: tokens → base → `@apply` component classes
- [x] Motion foundation: GSAP (brand eases, `useGsap`), `v-reveal`, reduced-motion support ([ADR 0003](../decisions/0003-motion.md))
- [x] Landing page v0 split into marketing sections
- [x] AI workflow: CLAUDE.md, skills, MCP servers, slash commands, docs
- [x] Strict Eloquent in non-production, destructive DB commands blocked in production

## Phase 1: Product core (MVP)

- [ ] `[?]` Final product brief, written to `docs/specs/` (who declares what, to whom)
- [ ] Domain model: `Declaration` (draft → signed → kept / broken / withdrawn)
- [ ] Create and edit a declaration: plain-language editor with live preview
- [ ] Sign flow: typed / drawn signature, timestamp, immutable snapshot
- [ ] Declaration detail page with status timeline
- [ ] Dashboard: my declarations, filters, empty states
- [ ] Policies and authorization tests for every action

## Phase 2: Witnesses & sharing

- [ ] Invite witnesses / counter-parties by email
- [ ] Counter-signing flow
- [ ] Public, read-only share link (signed URL)
- [ ] Notifications (mail + database) for invites, signatures and status changes
- [ ] Activity log / audit trail

## Phase 3: Keeping promises

- [ ] Due dates and reminders (scheduled jobs)
- [ ] Check-ins: mark as kept / broken, with an optional note
- [ ] Streaks and history visualisation
- [ ] Export as PDF (sealed certificate)

## Phase 4: Polish & launch

- [ ] Landing v1: real copy, social proof, motion pass (`find-animation-opportunities`)
- [ ] Accessibility audit (keyboard, contrast, reduced motion)
- [ ] Performance budget (LCP < 2.5s, JS per route)
- [ ] SEO: meta, OG images, sitemap
- [ ] Error tracking, uptime and backups
- [ ] Production deploy (Laravel Cloud, see the `deploying-to-cloud` skill)

---

## Changelog

| Date       | Change                          |
| ---------- | ------------------------------- |
| 2026-10-06 | Phase 0 foundation completed    |
