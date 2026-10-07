# Product

Durable product truth for designers and design skills (Impeccable reads this
file). The full story lives in [docs/product/](docs/product/vision.md); the
plan in [docs/roadmap/ROADMAP.md](docs/roadmap/ROADMAP.md).

## Platform

web

## Stack

Laravel 13, Inertia v3, Vue 3, shadcn-vue, Tailwind v4. Fonts self-hosted.

## Users

- **Guests** (the most users): every age, on a phone, often on mobile data,
  in de-CH, fr, it or en. They open one personal link and answer once.
- **Couples** (the buyers): 60+ guests, budget above CHF 30,000, design-aware,
  guests in more than one language or country.
- **Venues** (second customer and channel): host 15+ weddings a year and need
  correct final numbers without emails.

## Product Purpose

Premium wedding websites for Switzerland: an art-directed site, a personal
link per household, and the venue built in. Set up by hand within 48 hours.

## Positioning

Competes on finish, not price (CHF 390–1,900 against CHF 50–200 tools).
"Design is the product. Nothing ships with a visible bug."

## Operating Context

Guests: phones, one-handed, between other things, sometimes outdoors.
Couples: laptops and phones in the evening. Venues: office desktops in daylight.

## Capabilities and Constraints

Built today: personal links, event-aware invitation, four locales, four
starter themes (Ivory, Rosé, Alpine, Riviera), data deletion schedule, and a
question form on the landing page. Everything else is on the roadmap.
Never claim unbuilt features or invent customers, venues or numbers.

## Brand Commitments

- Visual world "Vorhang auf" (ADR 0009, [DESIGN.md](DESIGN.md)): the wedding
  evening as a stage. Archivo only (modern, heavy, no serif), night and porcelain, blush (rosé) as
  the one accent, real photography, a three.js veil reveal in the hero.
- Emotional and big, never corporate. Blue, cream paper and wax seals are retired.
- The landing page speaks Swiss German first, addressing couples as "ihr".
- Guest sites wear the couple's theme, not the Hereby brand.
- Guests never see ads or vendor offers (one venue credit line at most).

## Evidence on Hand

A demo wedding (`php artisan db:seed`: Anna & Luca, four households, four
languages). Landing photography is credited Unsplash stock
(`public/images/landing/CREDITS.md`). No customer photos or testimonials yet;
don't fake them, and never caption stock photos as customers.

## Product Principles

1. A guest answers in under 60 seconds on a phone, with no account and no app.
2. Every guest page loads in under 2 seconds on mobile data.
3. Guests never see ads or vendor offers.
4. Guest data stays in Switzerland and is deleted on schedule.
5. Fewer features, all finished.

## Accessibility & Inclusion

WCAG 2.2 AA on every surface, including every wedding theme. Touch targets of
44px or more, reduced-motion fallbacks, full keyboard paths, and real language
tags per household. Swiss German spelling (no "ß").
