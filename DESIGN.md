---
name: Hereby
description: Vorhang auf. The wedding evening as a stage — night and porcelain, one heavy grotesk, photography, a veil that parts.
colors:
    porcelain: 'oklch(0.977 0.009 22)'
    porcelain-bright: 'oklch(0.992 0.003 80)'
    night: 'oklch(0.185 0.012 50)'
    night-deep: 'oklch(0.145 0.01 50)'
    sand-quiet: 'oklch(0.93 0.012 75)'
    sand-line: 'oklch(0.885 0.016 72)'
    sand-text: 'oklch(0.46 0.02 55)'
    blush: 'oklch(0.8 0.09 7)'
    blush-ink: 'oklch(0.51 0.13 4)'
    moss: 'oklch(0.5 0.11 152)'
    ember: 'oklch(0.53 0.2 27)'
typography:
    display-hero:
        fontFamily: 'Archivo Variable, system-ui, sans-serif'
        fontSize: 'clamp(3.25rem, 8.6vw, 8.25rem)'
        fontWeight: 600
        lineHeight: 0.92
        letterSpacing: '-0.045em'
    display:
        fontFamily: 'Archivo Variable'
        fontSize: 'clamp(2.5rem, 6vw, 5.5rem)'
        fontWeight: 600
        lineHeight: 0.96
        letterSpacing: '-0.04em'
    headline:
        fontFamily: 'Archivo Variable'
        fontSize: 'clamp(2.1rem, 4.2vw, 4rem)'
        fontWeight: 600
        lineHeight: 1
        letterSpacing: '-0.035em'
    title:
        fontFamily: 'Archivo Variable'
        fontSize: '1.25rem → 1.5rem'
        fontWeight: 600
    body:
        fontFamily: 'Archivo Variable, system-ui, sans-serif'
        fontSize: '1rem → 1.0625rem'
        fontWeight: 400
        lineHeight: 1.65
    lede:
        fontFamily: 'Archivo Variable'
        fontSize: '1.125rem → 1.3125rem'
        lineHeight: 1.5
rounded:
    sm: '2px'
    md: '4px'
    lg: '6px'
    pill: '9999px'
    device: '2.6rem'
spacing:
    gutter: '20px / 32px / 48px'
    section: '96px / 128px / 160px'
components:
    button-primary:
        backgroundColor: '{colors.night}'
        textColor: '{colors.porcelain-bright}'
        rounded: '{rounded.pill}'
        height: '48px → 52px'
    button-on-stage:
        backgroundColor: '{colors.porcelain}'
        textColor: '{colors.night-deep}'
        rounded: '{rounded.pill}'
    stage:
        backgroundColor: '{colors.night-deep}'
        textColor: '{colors.porcelain}'
---

# Design System: Hereby

Values live in code: `resources/css/theme/palette.css` (raw), `semantic.css`
(tokens), `tailwind.css` (utilities). This file explains how to use them.
Decision record: [ADR 0009](docs/decisions/0009-vorhang-auf.md).

## Overview

**Vorhang auf.** The wedding evening as a stage. A sheer veil parts and
reveals the couple; the page alternates between candlelit **night** sections
and **porcelain** daylight, set in one heavy, slightly widened grotesk
(Archivo). Colour comes from photography; the interface itself stays black, white
and a whisper of blush. Big type, real photos, generous space: it should
feel like the opening pages of a bridal magazine, not a SaaS template.

Two surfaces, two looks (ADR 0004):

- **Platform** (marketing, auth, dashboard): this system. Marketing is light by
  default (`.surface-light`) with `.stage` night sections; the app is always
  light (no dark mode), with the night sidebar framing the porcelain workspace.
- **Guest sites** (`pages/invitation/*`): night ink on the theme's paper
  (Ivory cream, Rosé blush, Alpine stone, Riviera champagne, Lavanda mist)
  with the theme's accent, inside `WeddingThemeScope` (ADR 0012, 0014).
  Each template has its own display serif (Atelier Bodoni Moda, Garten
  Cormorant Garamond, Panorama Fraunces; `invitation-type.css`), Archivo
  for reading, small tracked capitals for dates and places, and a site bar
  with the couple's monogram (`InvitationNav`). Ivory is the house theme.

## Colors

Strategy: **photography first, monochrome UI, one metallic accent.**

- **Porcelain** (`background`, `card`): warm white, never yellow or cream.
- **Night** (`foreground`, `primary`, and the whole `.stage`): a warm black.
- **Sand** (`muted`, `muted-foreground`, `border`): secondary text and hairlines.
- **Blush** (`brand`): the accent. `blush-ink` on porcelain (AA), bright
  `blush` on night. Used for the italic accent line, numerals' units, the
  wordmark's full stop, icons in the FAQ, focus rings.
- **Moss / ember**: success and danger only.

### Named Rules

**The Stage Rule.** Dark sections use `.stage`, which re-declares the tokens,
so everything inside (buttons, rules, muted text) turns to night with no
`dark:` variants.

**The Candle Rule.** Blush is candlelight: thin and rare. Never a fill,
a gradient, a glow, a button background or a card border.

**The Token Rule.** Components use semantic tokens only. Raw scales
(`night-900`) live in `resources/css/` only; the after-edit hook flags them.

## Typography

**One family:** Archivo Variable (`wght` 100–900, `wdth` 62–125%, italic),
self-hosted. Hierarchy comes from size, weight and width; display lines are
semibold and widened (`font-stretch` 104–118%); body text is widened a little
(108%), a touch light (390) and tracked in slightly (−0.006em), so text and
headlines read as one voice rather than a default grotesk.

### Hierarchy

- **Display hero** (`.display-hero`): the first line only, up to 132px.
- **Display** (`.display`): statements, the closing line, "Hiermit gestrichen.", the footer declaration.
- **Headline** (`.headline`): section headings.
- **Title** (`.title`): rows and steps.
- **Step figure** (`.step-figure`): numerals that _are_ the information (20 Min., 48 Std.), counted up.
- **Accent** (`.accent`): Archivo italic in blush. One phrase per section at most.
- **Wordmark** (`.wordmark`): bold, `wdth` 118%, blush full stop.
- **Lede / body-copy / caption**: measure 42–58ch.

### Named Rules

**One family.** No serif anywhere on the platform (the founder chose the
modern grotesk over Bodoni on 2026-10-07). Contrast comes from scale,
weight, width and italic. Guest sites are the exception (ADR 0014): each
template sets its names and headings in its own serif.

**No labels.** No eyebrows, kickers or tracked uppercase labels above
headings, no monospace as decoration. The heading carries itself.

## Layout

- `page-container` (max 80rem, gutters 20/32/48px) and `section` (96/128/160px).
- Compositions are **asymmetric splits** (5:6, 7:5), full-bleed photo bands
  and rows divided by hairlines. Never a grid of equal cards.
- More space above a heading than below it. Sections alternate light and night.
- Guest pages are one mobile column (`invitation-page`) that grows into a
  spread from `lg` (`invitation-layout.css`): Atelier and Panorama set each
  practical chapter as a 5:7 split (heading left and sticky, content right,
  a hairline between, chapters rule to rule); Garten keeps its middle axis
  and sets place, travel, stay and dress code as arched cards two by two.
  The programme is a ledger (Atelier), a vine whose sides take turns
  (Garten) or a night band of up to four frames side by side (Panorama).
  The couple's other photos form a band after the calendar
  (`InvitationGallery`): a spread, arches, or one 21:9 frame, opening like
  a curtain as they scroll in.
- Guest sites carry the landing's moments (`invitation-scenes.css`): Atelier
  opens on the photo edge to edge with a sparkler drawing a heart of light
  over it, the names at up to 11rem, the days to go as a figure with an
  italic unit; the day is told in acts on a night band (photo, roman
  numeral, time, the place in the accent's italic) that pins and travels
  sideways on wide screens. Every template runs the day's parts past as a
  band (solid and outlined italic in turn), greets the household with its
  names in the accent's italic, and closes with the two rings turning above
  «Kommt ihr?»; Garten's petals fall in 3D, Panorama's lights drift. The
  couple's accent tints the practical chapters' ground and the footer
  (`--tint`, 8% of the accent in porcelain). 3D comes through `GuestScene`:
  only after the first scroll or touch, only near the screen, never on data
  saver, one still frame under reduced motion.

## Elevation & Depth

Flat. Depth comes from photography, scrims and the night/porcelain rhythm.
The only shadows belong to real objects: the phone
(`0 50px 90px -30px rgb(20 14 10 / 0.55)`) and the rings' soft floor shadow.
Scrims (`.hero-scrim`, `.venue-band-scrim`, `.closing-scrim`) are functional
gradients for legibility and live in CSS, never in templates.

## Shapes

Photos are square-cornered (2px). Buttons are pills. The phone frame is the
only large radius. Hairlines (`border-rule`) are the only line weight.

## Components

### Buttons

- `Button size="pill"`: 48–52px tall, rounded-full. Default variant is night on
  porcelain; inside `.stage` it inverts automatically.
- Secondary action: `variant="ghost"` pill, or `link-underline` text.

### Hero veil (signature)

`HeroSection` + `useVeilCurtain` + `lib/three/veil-curtain.ts`. The photo is a
plain eager `<img>` (LCP, preloaded in `landing-seo.blade.php`). Over it, two
sheer cloth panels drawn by a shader gather to the sides like curtains tied
back, then keep breathing in a slow wind and bulge gently around the pointer.

- States: `closed` (CSS veil) → `webgl` → `open`. Copy rises at ~1.1s.
- A click, tap or "Vorhang öffnen" skips to the end (700ms).
- Reduced motion: no veil at all. No WebGL or slow network (>1.6s): the CSS
  veil slides away instead.

### Rings (3D)

`RingsCanvas` + `useWeddingRings` + `lib/three/wedding-rings.ts`: blush gold
and platinum bands, studio-lit, following pointer and scroll. Mounted only when
near the viewport, paused off screen, one still frame under reduced motion.

### Invitation preview

`PreviewPhone` + `InvitationPreview`: the demo wedding re-addressed across four
households and languages (`useAutoCycle`), with DE/FR/IT/EN tabs.

### Signature sections (second half of the landing page)

Each one is a motion pattern with a product reason, not decoration:

- **Steps** (`StepsSection`, `StepItem`): numerals count up (`useCountUp`), a
  blush thread is drawn through them by scroll.
- **Ein Tag in fünf Akten** (`ActsSection`, `ActCard`): the wedding day as a
  play. Pinned on desktop, scroll moves exactly one act per step; photos
  parallax inside their frames; counter and progress bar. A swipe carousel
  below `lg`. Shows the product's core idea: each household sees its acts.
- **Marquee** (`MarqueeBand`): Hochzeit · Mariage · Matrimonio · Wedding,
  solid and outlined; scroll speeds it up and reverses it.
- **Studio** (`StudioSection`, `ShowcaseCarousel`, `ShowcaseWindow`,
  `ShowcaseHero*`, `ShowcaseBody*`, `ShowcaseFooter`, `ShowcaseDialog`): the
  three templates (Atelier, Garten, Panorama) as finished sites in browser
  windows of fixed height that stand still and fade out at the bottom. On
  hover (always on touch) a dark «Ganze Seite ansehen» pill rises into the
  middle; a click anywhere on the front window opens the whole site in a
  popup that grows out of the click (560 ms, drawer curve; fades only under
  reduced motion), where it scrolls, ends in the same footer as a real
  site, and offers «Mit diesem Design starten». A click on a peeking window
  brings it to the front. The windows sit side by side in a full-bleed scroll-snap track that loops endlessly
  (`useLoopingTrack`: three copies, the visitor always rests in the middle
  one and is moved there invisibly when the track settles) and opens with
  Garten in the middle, its neighbours peeking: swipe, or step with the
  arrows; the template's name and mood sit between them. Nothing moves
  on its own. Atelier sets the names over the photo, Garten puts the photo
  under an arch between two drawn twigs, Panorama runs the names as a band
  over a strip of photos. Each has its own 3D layer (`ShowcaseScene`, scenes
  from `lib/three`): Atelier's sparkler draws a heart of light over the photo, as in a long
  exposure, with sparks spraying off its tip (`lib/three/sparkler.ts`; the
  rings stay the intro's own),
  Garten's rose petals fall through the arch (smaller and more of them, via
  `createPetals` options), Panorama's bokeh drifts behind the band in a
  light tint of the accent. three.js loads only when a slide comes to the
  front, and only the front slide renders (`useThreeScene`'s `enabled`).
  Above, one sentence with blanks
  (`ShowcaseCoupleForm`, `useShowcaseCouple`) puts the visitor's names and
  date into all three, and three accent chips recolour only their accents
  (`--showcase-ink`); the ground stays porcelain. Below the heroes each
  template tells the same content (welcome, programme, travel, photos,
  reply) in its own way, revealed on scroll inside the window
  (`showcase-bodies.css`): Atelier as a magazine (pull quote with the
  accent «Ja», a ledger whose rules draw in, the venue photo wiping up under
  an overlapping caption card, an asymmetric grid, a large close); Garten as
  a garden (a drawn sprig, a vine timeline whose leaves pop out, the venue
  under an arch, one print dropped on the table between two photos framed by hand (a leaf wreath with a bow, a climbing rose round an arch) whose lines draw themselves before the leaves open, an arched reply card);
  Panorama as cinema (giant welcome, dark bands with frame cards that slide
  in, a 21:9 venue that pushes in slowly, a sprocketed film strip, a
  running «Kommt ihr?» ticker). Reduced motion keeps the fades only.
  Classes are `sc-*` so the real templates' styles never leak in.
- **Premium** (`PremiumSection`, `PremiumEvening`, `PremiumScreen`,
  `PremiumExtras`, after the studio, on `.stage`): two products, told apart.
  The Atelier package is one framed panel with a «Paket Atelier» pill: the
  night as a timeline (17:30 Gruppenfotos, 18:30 Tischplan, 21:00 Einlagen,
  00:10 Heimweg) beside a guest's phone (porcelain, `data-theme="ivory"`)
  that shows that moment's real screen; hover or tap picks a moment, nothing
  changes on its own. The extras are four cards, each led by a large Hereby
  icon on a soft blush disc: the icon draws itself in as its card rises
  (staggered), its blush dot pops in last; hover lifts the card and tilts
  the disc with a halo. A pill reads «In Couture inklusive» or «Extra».
  No prices, nothing marked «bald».
- **Hiermit gestrichen.** (`StruckSection`, `StruckRow`): what we leave out
  (Passwörter, Vorlagen, Werbung…), struck through by a blush line as each
  row scrolls in. A custom icon pops out of the struck word (spring, never from
  scale 0) and jumps bigger on hover; the answer fades in beside it.
- **Closing** (`ClosingSection`, `useClosingVeil`): the hero's veil drifts back
  in over the last photo, driven by scroll. The page opens and closes on the curtain.
- **Floating nav** (`FloatingNav`, `useActiveSection`): once the hero is behind
  you, a pill slides in at the top; a soft highlight glides to the chapter you
  are reading. On phones: logo, current chapter, CTA.
- **Hero line**: "Vorhang auf" in porcelain, "für euer Ja." in blush (same
  face, upright), with two hand-drawn swooshes under "Ja." written after the reveal.
- **Footer** (`SiteFooter`, `useFooterReveal`): everything on the left (tagline,
  lede, CTA, links), then the giant centred "hereby.". GSAP brings the tagline
  in word by word, the items one by one, then the wordmark letter by letter and
  the full stop with a bounce; masks are removed afterwards so nothing is
  clipped. Legal links: Datenschutz, Impressum (`pages/legal/*`).
- **Auth** (`layouts/auth/AuthStageLayout`): the stage beside the form. The
  photo settles, the veil draws open, "Vorhang auf für euer Ja." rises word by
  word; the logo, title and each form field arrive in turn.

### App surfaces (couples)

Operate mode: calm, familiar, fast. The brand lives in type, the night
panel and the live invitation, never in decoration.

- **Sidebar** (`AppSidebar`, `NavMain`, `sidebar/*`, `useAppNav`,
  `app-nav.css`): our own nav, not shadcn's. A `.stage` night column framing
  the porcelain workspace. The wordmark, then the couple's names in display
  type with the date, a blush countdown and the plan («Paket Signature»),
  then the nav with plain counts as badges. Full from 1024px (or the icon
  rail if the couple collapses it), the rail on tablets with the full nav
  laid over the page on the toggle, a drawer on phones. The look is chosen
  by media queries alone, through custom properties, so zooming never
  remounts or swaps it. Hover: one ink pill per group glides to the row
  under the pointer and back to the active one, the icon tips with a small
  overshoot and turns blush, the label steps 2px; the active row wears the
  wordmark's full stop. In the rail each row's name floats out as a pill.
- **Scroll rail** (`sidebar/ScrollRail`): the app page has no native
  scrollbar (it came and went with the page's length and pushed the
  workspace sideways). A slim porcelain pill in the night frame's margin
  shows while scrolling or near the pointer and can be dragged.
- **Setup** (`pages/setup/Step`, `components/setup/*`, `setup.css`): one
  question per screen, seven progress segments (reached steps link back), a
  sticky footer with "Zurück" and "Weiter", and on `lg` the **live
  invitation** on a `.stage` panel: the phone re-renders from the form as the
  couple types, in the chosen theme. Choices are native radios and checkboxes
  styled as `.choice` (large option) and `.chip` (short toggle).
    - **Scenes** (`lib/setupScenes.ts`): every step has its own landing photo
      behind the phone (couple, veil, lake jetty, candlelit table, sparklers,
      bouquet). It cross-fades (700ms opacity, a slow 1.04 settle) when the step
      changes, and the next one is preloaded. Under the phone, one **story
      line** retells the answers so far ("Der Ort folgt. Alles andere kann schon
      beginnen."). On phones the scene becomes a 128px band under the progress.
    - The step body fades over (≤260ms); after each save a quiet moss
      "Gespeichert" appears for ~2.5s. The first step reassures: "Rund zehn
      Minuten. Jeder Schritt wird gespeichert."

- **Dashboard** (`components/dashboard/*`, `dashboard.css`): couple names as the
  title with the date and place (the countdown lives in the sidebar only);
  "Heute für euch" (at most three computed actions as hairline rows);
  replies as **one bar and three rows** (never a donut or a hero metric), and
  one plain sentence instead while nobody has opened a link; the programme
  with invited counts (attending once replies exist; the day only when the
  wedding spans several). **Eure Einladung** (`InvitationPanel`) is the one
  `.stage` moment: the couple's invitation in their theme, rising out of the
  candlelit photo and cropped by the panel's bottom edge.
- **Konto** (`layouts/settings`, `content/account.ts`): same page title and
  column as the other app pages; Profil and Sicherheit as text tabs on
  a hairline (`.settings-tab`); sections separated by hairlines; deleting the
  account asks inline for the password, never a red box or a modal. On the
  right a sticky `.stage` panel (`AccountPanel`): the couple's names, what
  happens to their data and the day it is deleted, and how sign-in is held.
  Only claims that are true today (hosting is still open, ADR 0007).
- **Gäste** (`pages/guests/Index`, `guests.css`): search plus reply filters, one
  hairline row per household with a copy-link pill. The import panel opens
  inline (never a modal): paste, file or one by one, then a preview with
  duplicates flagged before anything is saved.
- **Household editor** (`components/guests/editor/*`): clicking a household
  opens a right sheet (full width on phones, 300ms in). Name, people (labels on
  the first row only), email, language, +1, the parts of the day as bordered
  checkbox rows with day and time, then the personal link. Renewing the link
  and removing the household are quiet text actions that ask once, inline
  ("Ja" / "Abbrechen"), never a second modal. The save button sits in a fixed
  footer.
- **Antwortformular** (`pages/rsvp/Settings`): the couple's menus and
  household questions on the left; on `lg` a `.stage` panel with the guest's
  reply at phone scale, re-drawn as they tick (chips fade in, 200ms).
- **Küche & Service** (`pages/kitchen/Index`): two quiet action cards (sheet,
  Excel), then one hairline block per part of the day with the head count in
  display type and menus as hairline rows. The printable sheet
  (`views/kitchen/sheet.blade.php`) is plain A4, night on white, no brand.
- Touch targets are 44px everywhere in the app: inputs default to `h-11`,
  `.chip`, `.pill-outline`, `.icon-button`, `.check-label`; small text links
  get `.hit-area`. Chips scroll sideways on phones (`.chip-row`).
- Reply status colours: never opened `muted-foreground/35`, opened
  `foreground/60`, answered `success`. Blush stays out of the app UI except
  focus and the sidebar countdown.

### Guest reply (`pages/invitation/Reply`, `components/rsvp/*`, `rsvp.css`)

Inside `WeddingThemeScope`, so theme tokens only and every string through
`t()`. One screen, never a wizard: the couple's names, "Kommt ihr?", then
**"Wir sind alle dabei"** (a full-width outline pill that fills when true),
then one block per part of the day with a row per person and two `.chip`
radios (Dabei / Leider nicht). Menus appear as chips under people who come to
the dinner; allergies sit behind one disclosure (open when something is on
file, write-only); the plus-one is named, never a counter; household
questions come last. A fixed bottom bar says how many answers are open and
holds "Antwort senden", disabled until nothing is missing. The invitation
then shows "Danke für eure Antwort", who is coming and "Antwort ändern".

### «Der Abend» (`components/evening/*`, `components/invitation/Evening*`, `pages/witness/*`)

Couple side: «Der Abend» is a ledger of the day's tools (Tischplan,
Einlagen, Gruppenfotos, Heimweg, Hochzeitszeitung), each its own settings
page in the editorial system; without Atelier each shows the lock note and
the way to the plans on a card over a blurred demo of the page
(`LockedPreview`, demo data in `content/locked-previews.ts`, never real
guests); Erinnerungen and the Hochzeitszeitung do the same. Guest side (`evening-guest.css`, text only, rule 2):
the household's table as a card with the couple's line per person, the way
home as a list with a quiet countdown pill in the last six hours, and a
called group photo as the one loud block (primary ground) for 20 minutes.
The offer to sign something up appears only after the reply. The witness
page has no app shell: one column, decisions as pill buttons, declined
items fade. Printed sheets (running order, newspaper) follow the kitchen
sheet: system sans, night ink on white, printed from the browser.

### Invitation opening (`components/invitation/InvitationOpening`)

The guest's first second: the couple's names and date rise (450ms, ease-out)
behind two sheer panels drawn from the theme's own tokens, which part like
the landing veil (900ms, ease-in-out) and leave the invitation underneath,
already rendered. Server-rendered and driven by CSS alone, so it plays from
the first paint before any script and never catches a tap; once per link
(localStorage), a tap or key ends it early, absent under reduced motion. The names
are `aria-hidden`: the page's real heading is the one that is read.

### Invitation content (`components/invitation/InvitationBlocks`, `blocks/*`)

After the programme, the couple's own words, each block under a hairline
with a `.title`: plain paragraphs at `text-lg` (a blank line is a new
paragraph), the venue with its official address and a plain "Route planen"
link (no map embed), the FAQ as native `<details>` with a chevron that turns,
travel as paragraphs, and stays as hairline rows (hotel, details, a quiet link).
The couple edits them in **Inhalte** (`pages/content/Index`): one card per
block, language tabs as a `.segmented` control (a dot marks an empty
language), "Wer sieht das?" as a native select, arrows to reorder, and the
same inline confirm as the guest editor to remove. The **preview** is the
real invitation with a night bar on top (`PreviewBanner`) to switch language.

### Email (`resources/views/vendor/mail`)

Laravel's markdown mail, re-themed: porcelain ground, night ink, a pill
button (hex in `themes/default.css`, since mail clients ignore CSS
variables). Mail to guests passes `brand` to `<x-mail::message>`, so the
header shows the couple's names, never only "Hereby"; the footer is the
localized credit line. Guest mail never carries allergy text.

### Icons (`components/brand/HerebyIcon.vue`)

Hereby's own set, never stock: 32px grid, 1.5px stroke, round caps, one
blush detail per icon. Strokes draw themselves when revealed (`.draw`).
Names: link, events, clock, key, template, language, calendar, megaphone,
forever, glass, dinner, music.

### Rows and FAQ

`point-row` (with an icon column), `faq-item` (native `<details>`, smooth height
via `::details-content` where supported) and `AskQuestionForm` beside the FAQ:
questions are stored (`inquiries`) and mailed to `HEREBY_INBOX`.

## Motion

Tokens: `--ease-out`, `--ease-in-out`, `--ease-drawer`; durations 100–320ms
for UI. The hero choreography is the one long moment (≈3s, skippable).
Scroll reveals: `v-reveal` + `reveal`. Scroll-linked values come from
`useScrollProgress` (modes `through`, `pinned`, `enter`; rAF-throttled, no
scroll-jacking). Every animation has a reduced-motion path. three.js is
imported dynamically and only from `lib/three/`.

**Micro-interactions** (`components/micro.css`, after `emil-design-eng`):

| Where                                | What                                                     | Why                      |
| ------------------------------------ | -------------------------------------------------------- | ------------------------ |
| Buttons, chips, pills, option cards  | `scale: 0.97` on `:active`, 160ms ease-out               | The UI heard the press   |
| Switch                               | knob stretches while held, then travels                  | Tactile feedback         |
| Arrow links (`text-action`)          | arrow leans toward its target, pointer devices only      | Where it leads           |
| Copy link, Save                      | content crossfades through a 2px blur (`swap`) to a tick | State change             |
| Figures                              | `v-count-up` once on mount, 700ms                        | The number you came for  |
| Ledger rows, admin rows, switch rows | settle in, 40ms stagger                                  | No jarring pop-in        |
| App pages                            | fade up 4px in 200ms                                     | Continuity between pages |
| Guest reply after a yes              | one burst of petals around "Danke!"                      | Rare moment: delight     |

Never start from `scale(0)`, never animate keyboard actions, hover only under
`(hover: hover) and (pointer: fine)`, UI under 300ms.

## App pages: the editorial system (`components/editorial.css`)

App pages read like a printed programme: `PageMasthead` (title over a firm
ink rule, actions as words), figures set in type between hairlines
(`figure-row`, `stat-figure`), and `ledger-section`s (title in a left column,
hairline rows on the right). No boxed cards, no icons in circles. Each
masthead carries its own lazy three.js scene in the margin (`MastheadScene`):
roses (Übersicht, Hochzeiten), rings (Gäste), petals (Inhalte), flutes
(Küche & Service), ribbon (Anfragen). The Antwortformular has none: its night
preview panel is its one moment.

## Guest templates (`WeddingTemplate`, `components/invitation/templates/`)

Layout and motion, independent of the accent (any template × any theme).
The first screens are the landing studio's, filled with the couple's own
photos (up to four, uploaded in «Inhalte», cover first; spec
2026-10-13-couple-photos). A tap on a photo there sets the spot every crop
keeps in view (the dot). Each still stands without a photo.

| Template | First screen                                                                | Without a photo                         | Story                          | Signature motion                                                 |
| -------- | --------------------------------------------------------------------------- | --------------------------------------- | ------------------------------ | ---------------------------------------------------------------- |
| Atelier  | names set large over the cover photo in a rounded frame, date · place       | the frame at night, an accent glow      | a drawn path through chapters  | letters drift apart on scroll, path scrubbed (DrawSVG)           |
| Garten   | the cover under an arch between hand-drawn branches, names centred below    | the arch in the accent's tint, initials | chapters in garden-gate arches | branches draw and leaves unfurl (CSS), arches open (clip-path)   |
| Panorama | a night band: names running past, up to three photos edge to edge, the date | the band without the strip              | film strip                     | title leans with scroll speed, the day pins and travels sideways |

Guest-page budget (rule 2): the first screen is CSS only. GSAP
(`useGuestMotion`) and three.js load on the guest's first scroll, touch or
key, never in the first paint; reduced motion keeps everything still.

## Wedding themes

Every theme is porcelain ground and night ink (ADR 0012); a theme is its
accent (`brand`, `primary`, `ring`, `brand-muted`), as the landing's
accent chips show it.

| Theme   | Accent              |
| ------- | ------------------- |
| Ivory   | Blush (house style) |
| Rosé    | Peony               |
| Alpine  | Lake blue           |
| Riviera | Night-sea indigo    |
| Lavanda | Lilac               |

The coloured accents share one lightness/chroma ladder (L 0.47, AA on
porcelain) and differ only in hue, so the collection reads as one family.

Starter themes until the designer's collection lands (roadmap 0.6).

## Do's and Don'ts

- **Do** lead with real photography. **Don't** use icon tiles, blobs or stock illustrations.
- **Do** set type big and heavy, widened for display. **Don't** add a serif or a second family.
- **Do** keep blush for one accent per section. **Don't** fill anything with it.
- **Do** make claims the product can keep today. **Don't** invent customers, numbers or features.
- **Do** credit every photo (footer + `public/images/landing/CREDITS.md`).
- **Don't** reintroduce blue fields, cream paper, wax seals, eyebrows or section numbers.
- **Don't** use the Swiss cross or official emblems as decoration (protected by law).
