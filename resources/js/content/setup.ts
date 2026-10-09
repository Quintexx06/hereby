/**
 * Copy for the couple's setup (Swiss German, "ihr"). One question per step;
 * the helper line says why we ask. Spec: docs/specs/2026-10-09-couple-setup-and-dashboard-design.md
 */
import type { Celebration, GuestEstimate, SetupStepKey } from '@/types/setup';
import type { EventType, Locale, WeddingTheme } from '@/types/wedding';

export const steps: Record<
    SetupStepKey,
    { label: string; question: string; helper: string }
> = {
    paar: {
        label: 'Ihr zwei',
        question: 'Wie heisst ihr?',
        helper: 'So begrüssen wir eure Gäste auf jeder Einladung.',
    },
    datum: {
        label: 'Datum',
        question: 'Wann ist euer grosser Tag?',
        helper: 'Ihr könnt es später noch ändern. Die Antwortfrist schlagen wir sechs Wochen vorher vor.',
    },
    ort: {
        label: 'Ort',
        question: 'Wo feiert ihr?',
        helper: 'Wir suchen im amtlichen Adressverzeichnis der Schweiz, damit eure Gäste genau dort ankommen.',
    },
    ablauf: {
        label: 'Ablauf',
        question: 'Wie sieht euer Tag aus?',
        helper: 'Jeder Gast sieht später nur die Teile, zu denen er eingeladen ist.',
    },
    gaeste: {
        label: 'Gäste',
        question: 'Wie gross wird eure Hochzeit?',
        helper: 'Eine grobe Schätzung reicht. Die Gästeliste macht es später genau.',
    },
    look: {
        label: 'Look',
        question: 'Welcher Look passt zu euch?',
        helper: 'Alles auf eurer Website folgt diesem Entwurf. Wir stimmen ihn danach von Hand auf euch ab.',
    },
    uebersicht: {
        label: 'Übersicht',
        question: 'Stimmt alles?',
        helper: 'Ein Klick, und eure Website steht. Danach ladet ihr eure Gäste ein.',
    },
};

export const actions = {
    next: 'Weiter',
    back: 'Zurück',
    saveAndExit: 'Speichern und später weitermachen',
    finish: 'Website erstellen',
    change: 'Ändern',
    preview: 'Vorschau',
};

export const couple = {
    first: 'Vorname',
    second: 'Vorname',
    placeholderOne: 'Anna',
    placeholderTwo: 'Luca',
};

export const date = {
    wedding: 'Hochzeitsdatum',
    deadline: 'Antworten bis',
    deadlineHint: 'Danach sind die Zahlen für Küche und Service fix.',
    daysLeft: (days: number) =>
        days === 1 ? 'noch 1 Tag' : `noch ${days} Tage`,
};

export const venue = {
    name: 'Name der Location',
    namePlaceholder: 'z. B. Hotel Vitznauerhof',
    address: 'Adresse',
    addressPlaceholder: 'Strasse, Nummer und Ort',
    searching: 'Suche…',
    noResults:
        'Keine Adresse gefunden. Prüft die Schreibweise oder gebt sie von Hand ein.',
    manual: 'Adresse von Hand eingeben',
    official: 'Amtliche Adresse gefunden',
    clear: 'Andere Adresse wählen',
    undecided: 'Wir haben noch keine Location',
    undecidedHint: 'Kein Problem. Ihr könnt sie später ergänzen.',
};

export const celebrations: Record<
    Celebration,
    { label: string; hint: string }
> = {
    day: {
        label: 'Bei Tageslicht',
        hint: 'Trauung am Nachmittag, Apéro, Abendessen',
    },
    evening: {
        label: 'Am Abend',
        hint: 'Trauung bei Sonnenuntergang, Dinner, Fest bis spät',
    },
    day_and_evening: {
        label: 'Tag und Nacht',
        hint: 'Von der Trauung bis zum letzten Tanz',
    },
};

export const eventTypes: Record<EventType, string> = {
    civil_ceremony: 'Ziviltrauung',
    ceremony: 'Trauung',
    reception: 'Apéro',
    dinner: 'Abendessen',
    party: 'Fest',
    brunch: 'Brunch',
    other: 'Programmpunkt',
};

export const programme = {
    addPart: 'Teil hinzufügen',
    customName: 'Eigener Name (optional)',
    remove: 'Entfernen',
    time: 'Zeit',
    day: 'Tag',
    days: {
        '-1': 'Am Vortag',
        '0': 'Am Hochzeitstag',
        '1': 'Am Tag danach',
    } as Record<string, string>,
};

export const estimates: Record<GuestEstimate, string> = {
    up_to_50: 'Bis 50',
    up_to_100: '50 bis 100',
    up_to_150: '100 bis 150',
    over_150: 'Über 150',
};

export const languages: Record<Locale, string> = {
    de_CH: 'Deutsch',
    fr: 'Français',
    it: 'Italiano',
    en: 'English',
};

export const guestsCopy = {
    size: 'Anzahl Gäste',
    languages: 'In welchen Sprachen lesen eure Gäste?',
    main: 'Hauptsprache',
    mainHint: 'Neue Gäste bekommen diese Sprache, bis ihr etwas anderes wählt.',
};

export const themes: Record<WeddingTheme, { name: string; mood: string }> = {
    ivory: { name: 'Ivory', mood: 'Porzellan und Tinte, zeitlos' },
    rose: { name: 'Rosé', mood: 'Ochsenblut und Blush, für Gartenfeste' },
    alpine: {
        name: 'Alpine',
        mood: 'Arvengrün und Gletscher, für Berg und See',
    },
    riviera: { name: 'Riviera', mood: 'Terrakotta und Butter, für den Süden' },
    lavanda: {
        name: 'Lavanda',
        mood: 'Flieder und Aubergine, für Sommerwiesen',
    },
};

export const look = { suggested: 'Unser Vorschlag' };

export const review = {
    couple: 'Ihr zwei',
    date: 'Datum',
    deadline: 'Antworten bis',
    venue: 'Location',
    programme: 'Ablauf',
    size: 'Grösse',
    languages: 'Sprachen',
    look: 'Look',
    open: 'Noch offen',
};
