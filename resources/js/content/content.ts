/** Copy for the couple's "Inhalte" editor and the preview (Swiss German, "ihr"). */
import type { ContentBlockType } from '@/types/wedding';

/** «Eure Fotos» on Inhalte (spec 2026-10-13-couple-photos). */
export const couplePhotosCopy = {
    title: 'Eure Fotos',
    hint: 'Bis zu vier Fotos von euch. Das erste steht gross oben auf eurer Seite, so wie auf der Startseite gezeigt. Tippt auf euer Gesicht im Foto: Diese Stelle bleibt immer sichtbar, auch wenn ein Bogen oder Rahmen das Foto anschneidet.',
    focus: 'Stelle wählen, die sichtbar bleibt',
    add: 'Foto hinzufügen',
    uploading: 'Wird hochgeladen …',
    cover: 'Titelbild',
    makeCover: 'Als Titelbild',
    remove: 'Foto löschen',
    alt: (index: number) => `Euer Foto ${index + 1}`,
};

export const contentPage = {
    title: 'Inhalte',
    lede: 'Was eure Gäste sonst per Nachricht fragen würden: wo, was anziehen, wie es zu euch kam. Jeder Block erscheint auf jeder persönlichen Einladung.',
    empty: 'Noch keine Inhalte. Fangt mit dem an, was ihr am häufigsten gefragt werdet.',
    add: 'Block hinzufügen',
    preview: 'Vorschau ansehen',
    previewLabel: 'Vorschau',
    previewHint:
        'So sehen es eure Gäste auf dem Handy, Stand eures letzten Speicherns.',
    save: 'Speichern',
    unsaved: 'Noch nicht gespeichert',
    leaveUnsaved:
        'Ihr habt Änderungen, die noch nicht gespeichert sind. Seite trotzdem verlassen?',
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
        hint: 'Ein paar Sätze zum Einstieg, dann eure Geschichte in Kapiteln. Auf der Website werden sie zu einem Weg, der sich beim Scrollen zeichnet.',
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

/** Labels for list entries: FAQ questions, hotels, or story chapters. */
export const itemLabels = {
    story: {
        name: 'Kapitel, z. B. «2019 · Zürich» oder «Der Antrag»',
        details: 'Was damals passiert ist, in zwei, drei Sätzen',
        add: 'Kapitel hinzufügen',
        remove: 'Kapitel entfernen',
    },
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
