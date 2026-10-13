/**
 * Demo data for the blurred previews behind a locked feature: enough to
 * show what the page becomes, never real guests.
 */
export type PreviewRow = { title: string; meta: string; status: string };

export const previewSeating = [
    {
        table: 'Tisch Rigi',
        guests: ['Anna', 'Luca', 'Mia', 'Noah', 'Lea', 'Elias'],
    },
    {
        table: 'Tisch Pilatus',
        guests: ['Sofia', 'Jonas', 'Emma', 'Liam', 'Nina', 'Tim'],
    },
    {
        table: 'Tisch Säntis',
        guests: ['Laura', 'David', 'Lina', 'Ben', 'Sara', 'Leon'],
    },
];

export const previewRows: Record<
    'programme' | 'groupPhotos' | 'wayHome',
    PreviewRow[]
> = {
    programme: [
        {
            title: 'Rede der Trauzeugin',
            meta: '21:15 · 5 Minuten',
            status: 'Im Programm',
        },
        {
            title: 'Quiz: Wer kennt das Paar?',
            meta: '21:40 · 10 Minuten',
            status: 'Im Programm',
        },
        {
            title: 'Lied der Cousinen',
            meta: '22:10 · 4 Minuten',
            status: 'Angemeldet',
        },
    ],
    groupPhotos: [
        { title: 'Familie der Braut', meta: '14 Personen', status: 'Apéro' },
        { title: 'Unihockey-Team', meta: '9 Personen', status: 'Apéro' },
        { title: 'Alle Gäste', meta: '86 Personen', status: 'Vor dem Essen' },
    ],
    wayHome: [
        {
            title: 'S7 nach Zürich HB',
            meta: '00:42 ab Rapperswil',
            status: 'Mail um 00:12',
        },
        {
            title: 'Letztes Schiff nach Luzern',
            meta: '23:55 ab Weggis',
            status: 'Mail um 23:25',
        },
        {
            title: 'Nachtbus N3 nach Bern',
            meta: '01:20 ab Thun',
            status: 'Mail um 00:50',
        },
    ],
};

export const previewNewspaper = {
    masthead: 'Die Hochzeitszeitung',
    issue: 'Sonderausgabe',
    lead: 'Wie alles im Skilift begann',
    stories: [
        'Zehn Dinge, die nur die WG weiss',
        'Das Rezept für Lucas Rösti',
        'Unterwegs in Lissabon',
    ],
};
