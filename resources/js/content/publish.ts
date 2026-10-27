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
        'Eure Gäste können antworten. Mit Atelier läuft auch der Abend von selbst, und ihr sammelt ihre Fotos.',
    buy: (price: number) => `Online stellen · ${chf(price)}`,
    upgrade: (price: number) => `Upgrade · ${chf(price)}`,
    owned: 'Gebucht',
    from: 'ab',
    soon: 'bald',
    popular: 'Mit Erinnerungen',
    notReady:
        'Die Zahlung ist noch nicht eingerichtet. Schreibt uns, wir schalten euch von Hand frei.',
    finishSetup:
        'Schliesst zuerst die Einrichtung ab, dann könnt ihr versenden.',
    free: 'Immer gratis: alle fünf Entwürfe, die Vorschau, und für eure Gäste alles.',
    fine: 'Einmalig, per Karte oder TWINT über Stripe. Eure Gäste sehen nie einen Preis.',
    previewLabel: 'Eure Seite',
};

/** «Anfragen» for Couture: a short form, no mail app needed. */
export const coutureRequest = {
    ask: 'Anfragen',
    title: 'Couture anfragen',
    lede: 'Erzählt uns kurz von eurer Hochzeit. Wir melden uns innert zwei Arbeitstagen per E-Mail.',
    email: 'Eure E-Mail-Adresse',
    message: 'Eure Hochzeit',
    placeholder:
        'Wann und wo heiratet ihr, wie viele Gäste kommen, was wünscht ihr euch?',
    send: 'Anfrage senden',
    sentTitle: 'Danke, eure Anfrage ist da.',
    sentLede: 'Wir melden uns innert zwei Arbeitstagen per E-Mail.',
    prefix: 'Couture-Anfrage: ',
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
        line: 'Dazu ein Abend, der von selbst läuft, und die Fotos eurer Gäste.',
        features: [
            { text: 'Alles aus Signature' },
            { text: 'Tischplan: Gäste sehen, wer mit ihnen am Tisch sitzt' },
            { text: 'Einlagen und Gruppenfotos, geführt von euren Trauzeugen' },
            { text: 'Heimweg: Erinnerung vor dem letzten Schiff' },
            { text: 'Fotos von allen: ein Upload-Link für alle Gäste' },
        ],
    },
    couture: {
        name: 'Couture',
        line: 'Wir gestalten und begleiten alles mit euch.',
        features: [
            { text: 'Alles aus Atelier' },
            {
                text: 'Hochzeitszeitung: eure Gäste schreiben, die Trauzeugen drucken',
            },
            { text: '«Wir fassen nach» und eigene Domain' },
            { text: 'Einrichtung und Übersetzung durch uns' },
        ],
    },
};

/** The nudge on Gäste and Übersicht while the links are still closed. */
export const publishBanner = {
    title: 'Eure Links sind noch geschlossen.',
    text: 'Gäste sehen eure Seite, sobald ihr ein Paket wählt. Ihr selbst könnt jeden Link schon jetzt ausprobieren.',
    action: 'Online stellen',
};
