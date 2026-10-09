/** Copy for the couple's "Inhalte" editor and the preview (Swiss German, "ihr"). */
import type { ContentBlockType } from '@/types/wedding';

export const contentPage = {
    title: 'Inhalte',
    lede: 'Was eure Gäste sonst per Nachricht fragen würden: wo, was anziehen, wie es zu euch kam. Jeder Block erscheint auf jeder persönlichen Einladung.',
    empty: 'Noch keine Inhalte. Fangt mit dem an, was ihr am häufigsten gefragt werdet.',
    add: 'Block hinzufügen',
    preview: 'Vorschau ansehen',
    save: 'Speichern',
    remove: 'Block entfernen',
    removeConfirm: 'Diesen Block mit allen Sprachen löschen?',
    up: 'Nach oben',
    down: 'Nach unten',
    visibility: 'Wer sieht das?',
    everyone: 'Alle Gäste',
    onlyEvent: (name: string) => `Nur Gäste von «${name}»`,
    customTitle: 'Eigener Titel (optional)',
    body: 'Text',
    bodyHint: 'Eine Leerzeile beginnt einen neuen Absatz.',
    venueFrom: (venue: string) =>
        `Name und Adresse kommen aus eurer Einrichtung: ${venue}.`,
    venueMissing:
        'Noch keine Location eingetragen. Ergänzt sie in der Einrichtung, dann erscheint sie hier mit Routenlink.',
    question: 'Frage',
    answer: 'Antwort',
    addQuestion: 'Frage hinzufügen',
    removeQuestion: 'Frage entfernen',
    missingLanguage:
        'Noch leer: Gäste in dieser Sprache sehen den Text in eurer Hauptsprache.',
};

export const blockTypes: Record<
    ContentBlockType,
    { label: string; hint: string; placeholder: string }
> = {
    story: {
        label: 'Unsere Geschichte',
        hint: 'Wie ihr euch kennengelernt habt, in ein paar Sätzen.',
        placeholder: 'Es begann an einem Sonntag am See…',
    },
    venue: {
        label: 'Ort',
        hint: 'Parkplätze, Eingang, was man wissen muss, um hinzufinden.',
        placeholder:
            'Parkplätze gibt es beim Bootshaus, der Eingang ist seeseitig.',
    },
    dress_code: {
        label: 'Dresscode',
        hint: 'Was ihr euch wünscht, und was die Schuhe aushalten müssen.',
        placeholder: 'Festlich, gerne sommerlich. Die Feier ist auf dem Rasen.',
    },
    faq: {
        label: 'Fragen & Antworten',
        hint: 'Kinder, Geschenke, Fotos, Übernachtung: was euch oft gefragt wird.',
        placeholder: '',
    },
    travel: {
        label: 'Anreise',
        hint: 'Zug, Auto, Parkplätze, Shuttle: wie man hinkommt und wieder heim.',
        placeholder:
            'Mit dem Zug bis Luzern, dann mit dem Schiff nach Vitznau (40 Minuten). Parkplätze gibt es beim Bootshaus.',
    },
    stay: {
        label: 'Übernachten',
        hint: 'Hotels in der Nähe, mit Zimmerkontingent oder Buchungscode.',
        placeholder: 'Wir haben in zwei Hotels Zimmer für euch reserviert.',
    },
};

/** Labels for list entries: FAQ questions, or hotels in the stay block. */
export const itemLabels = {
    faq: {
        name: 'Frage',
        details: 'Antwort',
        add: 'Frage hinzufügen',
        remove: 'Frage entfernen',
    },
    stay: {
        name: 'Hotel',
        details: 'Details: Preis, Kontingent, Code, Frist',
        add: 'Hotel hinzufügen',
        remove: 'Hotel entfernen',
        url: 'Website (optional)',
    },
};

export const previewCopy = {
    banner: 'Vorschau: so sehen eure Gäste die Einladung.',
    back: 'Zurück zur Übersicht',
};
