# Invitation content: story, venue, dress code, FAQ

**Status:** Implemented 2026-10-09 (decisions taken by the build). Roadmap: 1.2
**Product source:** [features](../product/features.md), rules 2 and 5 of the [vision](../product/vision.md)

## Problem

The personal link shows the couple's names, the date and the programme. Guests
still text the couple the same questions: where exactly, what to wear,
parking, can we bring the kids. The couple needs a few well-made blocks
for these questions, not a page builder.

## Scope

In:
- four block types: **Unsere Geschichte**, **Ort**, **Dresscode** and **Fragen & Antworten**, at most one of each
- per-block visibility: everyone, or only households invited to one part of the day (e.g. the venue note for the dinner)
- text per language the wedding uses. Guests read their household's language; if it is missing they get the wedding's main language
- the couple's editor ("Inhalte"): add, edit per language, reorder, hide or remove
- a couple-only **preview** of the invitation in any of their languages that doesn't count as "opened"

Later added (1.8, same model): **Anreise** (text) and **Übernachten** (an
intro plus hotels, each with details and an optional link; links must be
http(s)).

Out:
- photos and galleries (2.2)
- free layout, custom block types, rich text: blocks are plain paragraphs. A blank line starts a new paragraph

## Decisions

1. **Four fixed types, ordered by the couple.** Each type has a translated default
   title, so most couples never type one. A custom title is optional.
2. **The venue block knows the venue.** It shows the wedding's venue name and the
   official address, plus a "Route planen" link built from the address (a plain
   link: no map embed, nothing third-party loads on the page, rule 2). The
   couple adds a note (parking, entrance, shoes for the lawn).
3. **The FAQ is question/answer pairs** (up to 12), shown as native `<details>`.
4. **Missing translation falls back** to the main language, never to an empty block.
   A block with no text in either language is not shown.
5. **Preview** renders the real invitation page for a sample household invited to
   every part, with the reply button shown but inert.

## Data model

`content_blocks`

| Column       | Type                    |
| ------------ | ----------------------- |
| `wedding_id` | foreign key, cascade    |
| `type`       | `story`, `venue`, `dress_code`, `faq` (unique per wedding) |
| `event_id`   | nullable foreign key (null = everyone), null on delete |
| `position`   | unsigned small int      |
| `content`    | json: `{ "de_CH": { "title": "", "body": "", "items": [{ "question": "", "answer": "" }] }, "fr": {…} }` |

## Acceptance criteria (tests)

- [x] A household sees blocks for everyone and blocks for the events it is invited to, in order
- [x] Text comes in the household's language, falling back to the main language. Empty blocks are hidden
- [x] The venue block carries the venue name, address and a route link
- [x] The couple can create (one per type), update per language, reorder and delete, only on their own wedding
- [x] Visibility can only point to the couple's own events
- [x] The preview renders for the owner only, in the chosen language, without marking any household opened
