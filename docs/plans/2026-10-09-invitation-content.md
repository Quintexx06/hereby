# Plan: invitation content blocks

Spec: [2026-10-09-invitation-content-design](../specs/2026-10-09-invitation-content-design.md). Roadmap: 1.2

## Backend

- [x] Migration `content_blocks`, `ContentBlockType` enum, `ContentBlock` model + factory, `Wedding::contentBlocks()`
- [x] `ContentBlock::textIn()`: title/body/items in a locale with fallback, null when empty; `VisibleBlocks` action
- [x] `InvitationResource` gains `blocks` (visible to the household, localized, non-empty, ordered), venue block carries venue + route link
- [x] Couple: `ContentBlockController` (index, store, update, destroy) + `ReorderContentBlocksController`; requests scoped to the wedding's events and languages
- [x] `PreviewInvitationController`: sample household, chosen locale, owner only, no `opened_at`

## Frontend

- [x] `lang/*/invitation.php`: block titles, route link, preview banner
- [x] Guest: `components/invitation/blocks/*` (BlockText, VenueBlock, FaqBlock) + `InvitationBlocks.vue` + `PreviewBanner.vue`
- [x] Couple: `pages/content/Index.vue` + `components/content/*` (block editor with language tabs, FAQ items, visibility, order), sidebar item "Inhalte", preview links

## Verify

- [x] Feature tests for the acceptance criteria
- [x] `composer ci:check`; screenshots: editor (desktop, phone), invitation with blocks (phone), preview in French
