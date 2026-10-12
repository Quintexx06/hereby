# Paywall and «Erinnerungen»: design

Status: proposal, 2026-10-12. Refines the couple tiers in
[business.md](../product/business.md) and roadmap items 2.2, 3.2 and 3.8.

## The idea in one line

**Build for free, pay to send.** A couple can make their whole site (every
template, every colour world, all content, the preview with their own names)
without paying. The paywall sits at the moment the site becomes real: sending
personal links to guests. The upgrade beyond that sells one thing guests
leave behind: **memories** (photos, voice wishes, a film).

Why there: a couple who has built their site is invested, and has seen it
with their names on it. Paying to publish feels like ordering the printing,
not like being blocked.

## What stays free, always

| Free | Why |
| --- | --- |
| Everything for guests: personal link, reply, calendar, no account | Product rules 1 and 3: guests never see a paywall |
| The full builder: 3 templates, 5 colour worlds, all content blocks | The couple must fall in love before paying |
| Preview with their own names, photos and programme | The paywall's best argument is their own site |
| A save-the-date page (one public link, no RSVP) | Lets couples share early; every guest who opens it sees the craft |

## Tiers

| | Signature (CHF 390) | Atelier (CHF 890) | Couture (from CHF 1,900) |
| --- | --- | --- | --- |
| Personal links, 60-second reply, reminders, kitchen sheets | ✓ | ✓ | ✓ |
| Languages | 2 | 4 | 4, native-reviewed |
| **Fotos von allen**: an upload link and table QR codes, a gallery that collects every guest's photos | — | ✓ | ✓ |
| **Live-Fotowand**: the photos as a slideshow on the evening's screen | — | ✓ | ✓ |
| **Sprachgrüsse**: every guest can record a short voice wish | — | ✓ | ✓ |
| **Day-of mode**: live programme, find my table | — | ✓ | ✓ |
| **Der Film**: a 2–3 minute film cut from the best photos and voice wishes | — | Add-on CHF 190 | ✓ |
| Matching print files (save-the-date, menu, place cards) | — | ✓ | ✓ |
| Own domain, printed keepsake book, concierge setup | — | — | ✓ |

Upgrading is possible at any time, paying only the difference. Payment by
TWINT or card through a Swiss provider (Payrexx or Datatrans).

## The memories features

**Fotos von allen.** One link per wedding (and a QR code per table). A guest
opens it on the phone, picks photos, sends them: no app, no account, full
resolution. The couple sees everything in one gallery. The witnesses can hide
a photo before it reaches the wall (feature catalogue: "approved by the
witnesses"). After the wedding the gallery is also offered on each guest's
personal link ("Teilt eure Fotos vom Abend").

**Sprachgrüsse.** On the same page: "Hinterlasst ihnen einen Gruss". Hold to
record, up to 60 seconds, listen back, send. The couple gets them after the
wedding, not during (it is a gift, opened later).

**Der Film.** After the wedding: the best photos (sharp, not duplicated, no
closed eyes), set to music, with the voice wishes laid over them and their
words as subtitles. The couple chooses the music from a short licensed list
and approves the cut before downloading or sharing it.

## Where AI helps (and its guardrails)

Following the [AI principles](../product/vision.md#ai-principles): a person
checks every output, and guest data never leaves Switzerland.

| Use | What it does | Guardrail |
| --- | --- | --- |
| Photo curation | Picks the best photos for wall and film: sharpness, duplicates, closed eyes | No face recognition; runs on Swiss infrastructure (self-hosted model) |
| Voice to subtitles | Transcribes voice wishes for the film | Self-hosted speech model in Switzerland; the couple can edit every line |
| Story writer, translation | Drafts and translates the couple's texts | The couple approves; native review per language (1.6, 2.7) |

No guest photo, voice or name is sent to a model outside Switzerland. Where a
Swiss-hosted model is not yet good enough, that feature waits (rule 5).

## How the paywall looks

- **In the builder, nothing is locked.** The couple works freely.
- **"Einladungen versenden"** opens a calm page, not a modal wall: their own
  site in the browser frame from the landing studio, their names, and three
  tiers side by side. Signature is preselected. One button: «Vorhang auf».
- **Memories in the dashboard** show as a section with a real preview on the
  couple's own data (an empty gallery with their names, a sample film poster)
  under the brand veil, and one line: «Mit Atelier freischalten». No badges,
  no countdowns, no "upgrade" noise elsewhere.
- **Guests never see a price**, a lock or an upgrade prompt (rule 3).

## Data and deletion

Photos and voice wishes are guest data: stored in Switzerland (Infomaniak or
Exoscale object storage, ADR 0007), deleted on the same schedule as other
guest data (12 months after the wedding). Two months before deletion the
couple gets a download of the full gallery, the voice wishes and the film.

## Order of work (rule 5: fewer features, all finished)

1. Tier on the wedding, the «Einladungen versenden» paywall page and payment
   (roadmap 3.8, brought forward).
2. Fotos von allen + gallery + Live-Fotowand (roadmap 2.2). Needs the Swiss
   storage provider decided.
3. Sprachgrüsse.
4. Der Film, with photo curation and subtitles (roadmap 3.2).

Each step gets its own plan in `docs/plans/` and ships only when finished.

## Open questions

- Prices are hypotheses (Phase 0 tests them); is CHF 190 right for the film add-on?
- Music licensing for the film: a small licensed library, or no music at first.
- Which Swiss GPU host for the self-hosted models (Infomaniak AI, Exoscale GPU).
