import type { landingPhotos } from '@/content/landing-photos';

/** One step of a spotlight tour; `target` is resolved in config/tours. */
export type TourCopyStep = {
    target: string | null;
    photo?: keyof typeof landingPhotos;
    title: string;
    text: string;
    /** The highlighted control stays clickable and ends the tour. */
    interactive?: boolean;
};

/** The buttons and hints every tour shares. */
export const tourCopy = {
    open: 'Rundgang',
    next: 'Weiter',
    back: 'Zurück',
    done: 'Los geht’s',
    skip: 'Überspringen',
    close: 'Rundgang schliessen',
    progress: (step: number, total: number) => `${step} von ${total}`,
    tryIt: 'Probiert es aus: Klickt auf das Hervorgehobene.',
};

/** The couple's first visit to their overview. */
export const coupleTour: TourCopyStep[] = [
    {
        target: null,
        photo: 'bouquet',
        title: 'Willkommen bei eurer Hochzeit',
        text: 'Hier seht ihr auf einen Blick, wer kommt und was noch ansteht. In einer Minute zeigen wir euch, wo alles ist.',
    },
    {
        target: 'preview',
        title: 'Eure Website',
        text: 'So sehen eure Gäste die Seite, mit allen Änderungen, die ihr gespeichert habt.',
        interactive: true,
    },
    {
        target: 'stats',
        title: 'Die Antworten',
        text: 'Haushalte, Gäste, wer schon geantwortet hat und wer noch nicht. Die Zahlen sind immer aktuell.',
    },
    {
        target: 'today',
        title: 'Heute für euch',
        text: 'Was gerade wichtig ist, zum Beispiel Gäste, die ihren Link noch nie geöffnet haben.',
    },
    {
        target: 'invitation',
        title: 'Eure Einladung',
        text: 'Von hier teilt ihr die Links. Jeder Haushalt bekommt seinen eigenen und antwortet in unter einer Minute.',
    },
    {
        target: 'guests',
        title: 'Gäste',
        text: 'Eure Gästeliste: importieren, Haushalte bilden, Links verschicken und Antworten nachsehen.',
        interactive: true,
    },
    {
        target: 'content',
        title: 'Inhalte',
        text: 'Texte, Fotos, Ablauf und Fragen für eure Gäste. Alles, was auf der Website steht.',
        interactive: true,
    },
    {
        target: 'rsvp',
        title: 'Antwortformular',
        text: 'Was ihr eure Gäste fragt: Menü, Allergien, Übernachtung, Mitfahrgelegenheit.',
        interactive: true,
    },
    {
        target: 'help',
        title: 'Fragen?',
        text: 'Schreibt uns jederzeit. Wir antworten persönlich.',
    },
];
