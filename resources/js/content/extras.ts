/** Copy for «Extras»: what a couple adds to their plan (spec 2026-10-13). */
import { chf } from '@/content/publish';

export { chf };

export type AddonKey =
    | 'newspaper'
    | 'chase_service'
    | 'custom_domain'
    | 'keep_memories';

export type AddonOffer = {
    key: AddonKey;
    price: number;
    owned: boolean;
    included: boolean;
    buyable: boolean;
};

export const extrasPage = {
    title: 'Extras',
    lede: 'Was ihr zu eurem Paket dazunehmen könnt, einzeln und einmalig. Couture enthält alles ausser «Erinnerungen behalten».',
    buyShort: 'Dazunehmen',
    renewShort: 'Ein Jahr länger',
    owned: 'Gebucht',
    included: 'In eurem Paket',
    needsPlan: 'Zuerst braucht ihr ein Paket.',
    needsAtelier: 'Mit Atelier oder Couture.',
    keptUntil: (date: string) =>
        `Eure Erinnerungen bleiben bis ${new Date(date).toLocaleDateString('de-CH', { dateStyle: 'long' })}.`,
    notReady:
        'Die Zahlung ist noch nicht eingerichtet. Schreibt uns, wir schalten euch von Hand frei.',
};

export const addons: Record<
    AddonKey,
    { name: string; line: string; perYear?: boolean }
> = {
    newspaper: {
        name: 'Hochzeitszeitung',
        line: 'Eure Freunde schreiben Geschichten und schicken Fotos über einen Link. Die Trauzeugen drucken daraus eure Zeitung, in eurem Design.',
    },
    chase_service: {
        name: 'Wir fassen nach',
        line: 'Zwei Wochen vor der Frist rufen oder schreiben wir allen, die noch nicht geantwortet haben. Ihr müsst niemandem nachlaufen.',
    },
    custom_domain: {
        name: 'Eigene Domain',
        line: 'Eure Seite unter eurer Adresse, etwa anna-und-luca.ch. Wir verbinden sie für euch.',
    },
    keep_memories: {
        name: 'Erinnerungen behalten',
        line: 'Nach einem Jahr löschen wir die Daten eurer Gäste. Fotos, Grüsse und eure Zeitung bleiben so ein weiteres Jahr.',
        perYear: true,
    },
};

/** Demo content for each extra's small scene on the Extras page. */
export const addonScenes = {
    newspaper: { masthead: 'Die Hochzeitszeitung', lead: 'Wie alles begann' },
    chase: {
        open: 'offen',
        done: 'zugesagt',
        names: ['Familie Keller', 'Sofia & Jonas', 'Onkel Peter'],
    },
    domain: { before: 'hereby.ch/w/k7f2q', after: 'anna-und-luca.ch' },
};

export const newspaperPage = {
    title: 'Hochzeitszeitung',
    lede: 'Eure Freunde schreiben Geschichten und schicken Fotos über einen Link. Die Trauzeugen stellen die Zeitung zusammen und drucken sie. Ihr seht nur, wie viele Geschichten schon da sind: Den Rest lest ihr am Fest.',
    locked: 'Die Hochzeitszeitung ist ein Extra, in Couture schon dabei.',
    unlock: 'Zu den Extras',
    count: (n: number) =>
        n === 0
            ? 'Noch keine Geschichten'
            : `${n} ${n === 1 ? 'Geschichte' : 'Geschichten'} eingegangen`,
    contributeLabel: 'Link für eure Freunde und Familie',
    contributeHint:
        'Ohne Konto. Schickt ihn an alle, die etwas über euch erzählen können.',
    witnessLabel: 'Link für eure Trauzeugen',
    witnessHint:
        'Dort lesen sie die Geschichten, entfernen, was nicht passt, und drucken die Zeitung.',
};

export const domainCopy = {
    title: 'Eure Domain',
    label: 'Domain',
    placeholder: 'anna-und-luca.ch',
    hint: 'Kauft die Domain bei einem Anbieter eurer Wahl und tragt sie hier ein. Wir melden uns mit den DNS-Angaben und verbinden sie für euch.',
    pending: 'Wartet auf Verbindung. Bis dahin laufen eure Links über hereby.',
    connected: 'Verbunden. Alle persönlichen Links laufen über eure Domain.',
    save: 'Domain speichern',
    saved: 'Gespeichert',
};
