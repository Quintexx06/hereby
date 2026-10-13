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
    buy: (price: number) => `Dazunehmen · ${chf(price)}`,
    renew: (price: number) => `Ein Jahr länger · ${chf(price)}`,
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
