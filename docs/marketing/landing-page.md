# Landing page: SEO and UX analysis

Status: implemented 2026-10-07 (roadmap 0.3, in progress). Copy:
`resources/js/content/landing.ts`; SEO strings and FAQ:
`lang/de_CH/landing.php`; design: [DESIGN.md](../../DESIGN.md).

## 1. Who lands here, and what they need in five seconds

| Visitor | Arrives from | Must understand | Must do |
| ------- | ------------ | --------------- | ------- |
| Engaged couple, 28–38, Deutschschweiz | Google ("Hochzeitswebsite Schweiz"), Instagram, a friend's invitation | This is a wedding website, it is personal per guest, it is done for us | Request a demo |
| Venue / planner | Our pitch, TrauDich! fair | Guests get everything without calls; final numbers arrive organised | Talk to us |
| A guest who saw a Hereby invitation | Footer credit | Who made this | Remember the name |

The page answers in this order: **emotion → what it is → why it is
different → how it works → proof of care (venue, design, Swiss details) →
objections (comparison, FAQ) → one action.**

## 2. Language

Swiss German first. The market is the Deutschschweiz, roadmap 0.3 asks for a
German page, and every competitor ranks with German pages. Copy uses Swiss
spelling (no "ß": *Gletscherweiss*), Swiss terms (*Apéro*, *ÖV*, *Ziviltrauung*)
and addresses the couple as **ihr**, the norm for wedding communication in
Switzerland. `<html lang="de-CH">` is set server-side. English, French and
Italian landing pages can follow with hreflang once the German page converts.

## 3. Keyword research (Google Autocomplete, gl=ch, hl=de-CH, 2026-10-07)

No paid volume tool was used; autocomplete is a demand signal, not a volume
figure. Re-check in Search Console after launch.

| Cluster | Observed queries | Intent | Where we answer |
| ------- | ---------------- | ------ | --------------- |
| Core | hochzeitswebsite, hochzeitswebseite, hochzeitshomepage, hochzeit website | Commercial | `<title>`, meta description, H1 statement, FAQ 1 |
| Local | hochzeitswebsite schweiz, hochzeitshomepage schweiz, **beste hochzeitswebsite schweiz**, hochzeitswebsite erstellen schweiz | Commercial, local | `<title>`, structured data (`areaServed: Schweiz`), "Gemacht für Hochzeiten in der Schweiz" |
| Privacy | **hochzeitshomepage mit passwort** | Problem-aware | Guest row "Kein Passwort, keine App", FAQ 2, comparison row "Zugang" |
| Invitation | hochzeitseinladung online, … online verschicken, … **online link**, hochzeitshomepage statt einladung | Commercial | Intro statement, H1 statement, guests section |
| RSVP | hochzeit rsvp online, hochzeit anmeldung gäste, hochzeit gästeliste online | Task | Step "60 Sek.", FAQ 3 |
| Free | … kostenlos, gratis, canva | Price-led | **Deliberately not targeted.** We do not compete on free (vision.md); FAQ 7 states the price honestly instead. |

Primary keyword: **Hochzeitswebsite Schweiz**. Secondary: **Hochzeitshomepage**
(used in FAQ 1 and the intro so both spellings are covered).

### On-page SEO checklist (all implemented)

- `<title>` (61 chars, the keyword first so truncation never cuts it): *Hochzeitswebsite Schweiz – persönlich für jeden Gast - Hereby*
- Meta description (≈150 chars) with primary keyword, the differentiator, and proof (60 s, vier Sprachen, 48 h).
- One H1 holding both the emotional line and the descriptive statement with the keyword.
- H2s per section, logical outline; the decorative phone preview uses no headings.
- Canonical, Open Graph and Twitter tags, a 1200×630 `og-image.jpg`.
- JSON-LD: `ProfessionalService` (areaServed CH, languages, price range) and
  `FAQPage` built from the same array the page renders, so they never disagree.
- All of the above rendered server-side (Blade) so crawlers and WhatsApp/iMessage
  previews never depend on JavaScript.
- Images: descriptive German `alt`, explicit `width`/`height` (no layout shift),
  `loading="lazy"` below the fold, art-directed portrait crops on phones.
- Personal invitation links stay `noindex` (ADR 0005).

### Still to do (needs a decision or a domain)

- ~~Production domain and `APP_URL`, then submit a sitemap in Search Console.~~
  Done 2026-10-15: `/sitemap.xml` (landing, topic pages, Showcase venues,
  legal) and a generated `/robots.txt` that announces it. Submit
  `https://hereby.ch/sitemap.xml` in Search Console.
- Impressum and Datenschutzerklärung pages (legally required in CH before launch).
- SSR in production (`npm run build:ssr`) so the full page text is in the HTML.
- A real demo-request form (CTAs currently go to registration; roadmap 0.3).

## 4. Competitor positioning (Swiss SERP, 2026-10-07)

| Competitor | H1 / promise | Model | Gap we use |
| ---------- | ------------ | ----- | ---------- |
| Loveplanr | "Die Hochzeitswebseite, die eure Gäste begeistert" | Free start, one-time packages, hosted in CH | One site for everyone, password protection as a feature |
| yesforever.ch | "Einfach eine schöne Hochzeitswebsite erstellen" | CHF 9.80/month | DIY, cheap, generic templates |
| WeddingDonkey | Free homepage with wish list | Free | DIY |
| Vjeny | Hochzeitswebsite Schweiz, three languages | CHF 99 one-time | No Italian or French, DIY |

All four sell **a builder**. None leads with a personal invitation per
household, done-for-you setup, or four Swiss languages per household. That is
the message hierarchy of this page.

**Update 2026-10-21 (founder):** lead with **premium design**. The
art-directed international builders (Riley & Grey, Minted, editorial
Squarespace and Wix templates) win couples on look: a serif per design,
warm paper, a monogram, live previews. Hereby now leads with "Premium-
Hochzeitswebsites aus der Schweiz. Gestaltet wie ein Magazin, persönlich für
jeden Gast", shows the templates right under the hero, and lets every
template be opened live (`/beispiel/{atelier|garten|panorama}`). The
personal link per household stays as the proof underneath.

**Update 2026-10-22 (founder):** the page builder is off (ADR 0015). Hereby
sells **five finished designs** (Salon, Raster, Garten, Arve, Lido), each
with its own faces, palette and layout; couples fill in photos and texts,
anything beyond is Massanfertigung. The studio shows the five as their real
first screens (desktop in a browser window, phone beside it, generated from
`/beispiel/{design}` into `public/images/designs/`) with «Live ansehen».
No hand-built mock sites, no accent picker: what the landing shows is what
guests get. Old sample links redirect (`atelier` → Salon, `panorama` → Arve).

**Update 2026-10-23 (founder):** after the review of the samples, each
design has its own idea and moments (ADR 0016): Salon a salon at night with
gilt frames, Raster the Swiss grid with pictograms, Garten restored with
its arch, branches and petals, Arve a Swiss map with a signpost, Lido the
lake bath with the sun cut-out. The setup's moods describe them in these
words; couples may upload eight photos.

**Update 2026-10-23, later (founder):** the studio is a carousel again, as
before ADR 0015: the front window large, its neighbours peeking at either
side, endless, with arrows and the five names; each slide holds the desktop
screenshot in a browser window and the phone over its corner. It stays
right after the hero. The landing now says what things cost, because the
founder found it unclear: a price line under the hero's buttons
(«Gestalten ist gratis. Online geht eure Seite einmalig ab CHF 290.»), the
Atelier badge and every extra with its price, and a section «Was es
kostet» (`#preise`, in the nav) with what stays free, the three plans as a
price list (Signature «nur die Website», Atelier with the evening, Couture)
and the extras. Prices come from `config/hereby.php` through
`WelcomeController`, so the landing always shows what checkout charges;
plan contents are the checkout's lines (`content/publish.ts`).

**Update 2026-10-23, evening (founder):** less body text. The studio's lede
is one line («Fünf Entwürfe, fertig gestaltet. Ihr bringt die Fotos.»), the
demo caption is gone, Garten comes first, and the arrows and the five names
sit together in one centred row. The hero's price line names the figure
(«Eure Website ab **CHF 290**, einmalig.»). «Was es kostet» leads with the
smallest package on the night stage (Signature as «Eure Hochzeitswebsite»,
its price large, a phone with Garten rising from the panel's edge), then
Atelier and Couture side by side («Wenn ihr mehr wollt»), then the extras
as one panel of five price tags. No «CHF 0» card: that building is free is
said beside the button.

**Update 2026-10-26 (founder):** the hero's price moved into the lede
(«… Gestalten ist gratis, online ab **CHF 290**, einmalig.», the figure
links to Preise); no separate line under the buttons. Atelier and Couture
are two tinted panels led by their icons; the fine print under the extras
is gone. The hero copy shows at the latest 0.9 s after load, whatever the
3D veil does (LCP on slow phones).

**Update 2026-10-28 (founder):** the Massanfertigung had to be said, not hidden in the extras list. The hero lede names it («… oder eine Seite, massgeschneidert von uns.»), the floating nav has «Nach Mass», and right after the studio a night-stage panel *Oder ganz nach Mass.* says what it is (your colours, your type, your layout, by hand), how it goes (Ihr erzählt · Wir gestalten · Ihr gebt frei) and what it costs (ab the bespoke add-on price, in Couture). A FAQ answers «Gestaltet ihr auch eine ganz eigene, massgeschneiderte Hochzeitswebsite?» for the FAQPage data.

## 5. Message hierarchy and section rationale

| # | Section | Job | UX notes |
| - | ------- | --- | -------- |
| 1 | Hero: *Vorhang auf für euer Ja.* | Emotion and the brand moment, then the promise: premium wedding websites, designed like a magazine, personal for every guest | The veil reveal is the memory of the visit. Copy reveals during the opening; skippable; LCP is the photo itself, preloaded. Secondary CTA "Vorlagen ansehen" scrolls to the studio. |
| 2 | Studio: *Gestaltet, nicht zusammengeklickt.* | "Design is the product", proven live, first | The three templates in their own serifs on their paper (ADR 0014), in browser windows in a carousel; visitors type their names and date; accent chips recolour the accents; «Live ansehen» opens the real site for the sample couple, reply form included. |
| 2b | Nach Mass: *Oder ganz nach Mass.* | Say we also build a site to measure, before anyone files us under templates | Night-stage panel right under the carousel: three steps, the price, the same primary CTA; the briefing itself lives in the app (pages/bespoke). |
| 3 | Intro: *Eure Gäste bekommen keine Website. Sie bekommen ihre Einladung.* | Reframe the category | Names the pains of the standard site (wrong events, irrelevant fields, passwords). |
| 4 | Für Gäste: *Ein Link pro Haushalt. In seiner Sprache.* | Show the mechanism | Live phone preview in DE/FR/IT/EN, real seeded data, labelled as an example. |
| 5 | Ablauf: *10 Min. · 1 Link · 60 Sek.* | Lower effort anxiety | The durations are the information, so they are the largest type. |
| 6 | Ein Tag in fünf Akten + Marquee | Show event-aware invitations and the venue (logistics, kitchen numbers) | Pinned horizontal track, one act per scroll step; demo data labelled as an example. |
| 7 | Hiermit gestrichen. | Objection handling and keyword coverage (Passwort, Vorlage, Werbung, Sprache) | Replaces the comparison table: struck-through words with what we do instead. |
| 8 | FAQ | Long-tail SEO and last objections | Native `<details>`, FAQPage structured data. |
| 9 | Closing: *Euer Sommer 2027 beginnt mit einer Einladung.* | One action, with urgency from the season | The veil drifts back in (bookend to the hero). Same CTA label everywhere: **Kostenlos starten**. |
| 10 | Footer: *Hiermit versprechen wir euch:* | Trust, brand memory | Four kept promises, a self-writing signature, a giant wordmark. |

CTA: one primary label across the page ("Kostenlos starten", to sign-up:
building is free, couples pay to send), always a pill, top
right and at the end of hero and closing. The secondary action ("Vorlagen
ansehen") scrolls to the studio; it never competes with the primary.

## 6. Claims policy

Every claim on the page must be true today or a service promise we deliver by
hand (concierge MVP). Removed or softened during review: "Gästedaten bleiben in
der Schweiz" (hosting not chosen yet, 0.8) and "finale Zahlen ohne eine einzige
E-Mail" (venue dashboard is Phase 2; now: export *auf Knopfdruck*). Prices are
the Phase 0 hypotheses from `docs/product/business.md` (ab CHF 390).

## 7. Performance budget (landing)

- Hero photo: 110 kB (phone) / 128 kB (desktop) WebP, preloaded with `fetchpriority=high`.
- Page JS for the landing route: ~6 kB gzip; three.js (~133 kB gzip) is loaded
  only after the hero photo is decoded and never blocks content.
- Fonts: Archivo only (latin normal + italic), self-hosted, `font-display: swap`.
- Below-the-fold images lazy-load; the rings mount only near the viewport.

## Sources

- [Hochzeitshomepage-Vergleich, trusted.de](https://trusted.de/hochzeitshomepage)
- [Loveplanr Hochzeitswebsite](https://loveplanr.com/hochzeitswebsite/) · [yesforever.ch](https://www.yesforever.ch/) · [Vjeny](https://vjeny.com/de/hochzeitswebsite-schweiz) · [WeddingDonkey](https://www.weddingdonkey.ch/)
- [Landing page best practices 2026 (hero clarity, 5-second test)](https://entreresource.com/landing-page-best-practices/)

## 8. Topic pages (added 2026-10-15)

Eight subpages, one search intent each, content in `lang/de_CH/pages.php`
(prices read from config), rendered by `pages/topics/Show.vue` with
server-side meta, canonical, BreadcrumbList and FAQPage data. Linked from
every marketing footer and to each other («Weiterlesen»).

| URL | Primary keyword | Intent |
| --- | --- | --- |
| /hochzeitswebsite-erstellen | hochzeitswebsite erstellen (schweiz) | Commercial |
| /hochzeitseinladung-online | hochzeitseinladung online (verschicken) | Commercial |
| /hochzeit-rsvp-online | hochzeit rsvp online, anmeldung gäste | Task |
| /gaesteliste-hochzeit | hochzeit gästeliste online | Task |
| /hochzeitshomepage-passwort | hochzeitshomepage mit passwort | Problem-aware |
| /mehrsprachige-hochzeitswebsite | mehrsprachige hochzeit, zweisprachig | Commercial, local |
| /preise | hochzeitswebsite kosten / preis | Price |
| /fuer-locations | for venues, photographers, planners | B2B |

The landing copy was rewritten at the same time to match the product as it
ships: self-serve setup in ten minutes (not «20 Min. Gespräch, 48 Std.»),
prices from config (not «ab CHF 390»), and the keywords RSVP, Gästeliste
and WhatsApp where users look for them.

## 9. Structured data and technical SEO (2026-10-15)

Every public page (landing, topic pages, legal, Showcase venues) renders its
meta tags and one connected schema.org graph on the server
(`App\Support\Seo\PageMeta`, `StructuredData`, `partials/seo.blade.php`):

| Node | Where |
| --- | --- |
| Organization (logo, contact, area served CH, languages) | every public page |
| WebSite, WebPage (or AboutPage for the imprint) | every public page |
| BreadcrumbList | every page but the landing |
| FAQPage | landing and topic pages, from the same array the page renders |
| SoftwareApplication with AggregateOffer in CHF (prices from config) | landing and /preise |
| EventVenue with PostalAddress, capacity and photos | /orte/{slug} |

Plus canonical, hreflang de-CH and x-default, Open Graph and Twitter tags
(a venue shares its own first photo), `max-image-preview:large`, and
`noindex, nofollow` on every non-public page. Titles stay within 60
characters and descriptions within 160 (tested).
