/** Copy for «Einladungen versenden», the paywall (spec 2026-10-12). */
import type { PlanKey } from '@/types';

/** Whole francs the Swiss way: 1’490. */
export const chf = (amount: number): string =>
    `CHF ${amount.toLocaleString('de-CH')}`;

export const publishPage = {
    title: 'Vorhang auf.',
    titlePublished: 'Eure Links sind offen.',
    lede: 'Eure Seite ist gebaut. Mit einem Paket werden die persönlichen Links eurer Gäste aktiv, einmal bezahlt, bis ein Jahr nach der Hochzeit.',
    ledePublished:
        'Eure Gäste können antworten. Mit Atelier sammelt ihr auch ihre Fotos und Grüsse.',
    buy: (price: number) => `Freischalten · ${chf(price)}`,
    upgrade: (price: number) => `Upgrade · ${chf(price)}`,
    owned: 'Gebucht',
    ask: 'Anfragen',
    from: 'ab',
    soon: 'bald',
    popular: 'Mit Erinnerungen',
    notReady:
        'Die Zahlung ist noch nicht eingerichtet. Schreibt uns, wir schalten euch von Hand frei.',
    finishSetup:
        'Schliesst zuerst die Einrichtung ab, dann könnt ihr versenden.',
    free: 'Immer gratis: der ganze Baukasten, alle Vorlagen und Farben, die Vorschau, und für eure Gäste alles.',
    fine: 'Einmalig, per Karte oder TWINT über Stripe. Eure Gäste sehen nie einen Preis.',
    previewLabel: 'Eure Seite',
};

/** What each plan holds; `soon` marks what ships before the season. */
export const plans: Record<
    PlanKey,
    { name: string; line: string; features: { text: string; soon?: boolean }[] }
> = {
    signature: {
        name: 'Signature',
        line: 'Alles, damit eure Gäste antworten.',
        features: [
            { text: 'Ein persönlicher Link für jeden Haushalt' },
            { text: 'Antworten in unter einer Minute, ohne Konto' },
            { text: 'Erinnerungen 14, 7 und 2 Tage vor der Frist' },
            { text: 'Küche & Service, Kalender für jeden Gast' },
        ],
    },
    atelier: {
        name: 'Atelier',
        line: 'Dazu alles, was eure Gäste euch zurücklassen.',
        features: [
            { text: 'Alles aus Signature' },
            {
                text: 'Fotos von allen: Upload-Link und QR auf den Tischen',
                soon: true,
            },
            { text: 'Live-Fotowand am Abend', soon: true },
            { text: 'Sprachgrüsse eurer Gäste', soon: true },
        ],
    },
    couture: {
        name: 'Couture',
        line: 'Wir gestalten und begleiten alles mit euch.',
        features: [
            { text: 'Alles aus Atelier' },
            { text: 'Euer Film aus Fotos und Sprachgrüssen', soon: true },
            { text: 'Eigene Domain und gedrucktes Erinnerungsbuch' },
            { text: 'Einrichtung und Übersetzung durch uns' },
        ],
    },
};

/** The nudge on Gäste and Übersicht while the links are still closed. */
export const publishBanner = {
    title: 'Eure Links sind noch geschlossen.',
    text: 'Gäste sehen eure Seite, sobald ihr ein Paket wählt. Ihr selbst könnt jeden Link schon jetzt ausprobieren.',
    action: 'Einladungen freischalten',
};
