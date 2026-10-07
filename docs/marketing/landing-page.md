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

- Production domain and `APP_URL`, then submit a sitemap in Search Console.
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

## 5. Message hierarchy and section rationale

| # | Section | Job | UX notes |
| - | ------- | --- | -------- |
| 1 | Hero: *Vorhang auf für euer Ja.* | Emotion and the brand moment, then what it is in one sentence | The veil reveal is the memory of the visit. Copy reveals during the opening; skippable; LCP is the photo itself, preloaded. |
| 2 | Intro: *Eure Gäste bekommen keine Website. Sie bekommen ihre Einladung.* | Reframe the category | Names the pains of the standard site (wrong events, irrelevant fields, passwords). |
| 3 | Für Gäste: *Ein Link pro Haushalt. In seiner Sprache.* | Show the mechanism | Live phone preview in DE/FR/IT/EN, real seeded data, labelled as an example. |
| 4 | Ablauf: *20 Min. · 48 Std. · 60 Sek.* | Lower effort anxiety | The durations are the information, so they are the largest type. |
| 5 | Ein Tag in fünf Akten | Show event-aware invitations and the venue (logistics, kitchen numbers) | Pinned horizontal track, one act per scroll step; demo data labelled as an example. Replaces the generic "Location" band. |
| 6 | Marquee + Theme studio | "Design is the product", proven live | The section re-themes itself (Ivory/Alpine/Riviera) with real theme tokens; the card tilts. |
| 7 | Hiermit gestrichen. | Objection handling and keyword coverage (Passwort, Vorlage, Werbung, Sprache) | Replaces the comparison table: struck-through words with what we do instead. |
| 8 | FAQ | Long-tail SEO and last objections | Native `<details>`, FAQPage structured data. |
| 9 | Closing: *Euer Sommer 2027 beginnt mit einer Einladung.* | One action, with urgency from the season | The veil drifts back in (bookend to the hero). Same CTA label everywhere: **Demo anfragen**. |
| 10 | Footer: *Hiermit versprechen wir euch:* | Trust, brand memory | Four kept promises, a self-writing signature, a giant wordmark. |

CTA: one primary label across the page ("Demo anfragen"), always a pill, top
right and at the end of hero and closing. The secondary action ("So
funktioniert's") scrolls; it never competes with the primary.

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
