/** Copy for «Der Abend», the couple's side (spec 2026-10-13). */

export const eveningPage = {
    title: 'Der Abend',
    lede: 'Wer wo sitzt, wer wann spricht, wer auf welches Foto gehört und wie alle heimkommen. Damit der Abend von selbst läuft.',
    locked: 'Der Abend ist Teil von Atelier und Couture. Eure Gäste sehen dann ihren Tisch, ihren Heimweg und den Aufruf zum Gruppenfoto.',
    unlock: 'Pakete ansehen',
    open: 'Öffnen',
    sections: {
        seating: {
            title: 'Tischplan',
            body: 'Wer neben wem sitzt, mit einer Zeile zu jeder Person.',
        },
        wayHome: {
            title: 'Heimweg',
            body: 'Die letzten Verbindungen der Nacht. Eure Gäste bekommen eine halbe Stunde vorher eine Mail.',
        },
    },
    wayHomeStatus: (count: number) =>
        count === 0
            ? 'Noch keine Verbindung'
            : `${count} ${count === 1 ? 'Verbindung' : 'Verbindungen'}`,
    seatingStatus: (s: {
        published: boolean;
        seated: number;
        guests: number;
    }) =>
        `${s.seated} von ${s.guests} gesetzt · ${s.published ? 'für Gäste sichtbar' : 'noch nicht sichtbar'}`,
};

export const seatingPage = {
    title: 'Tischplan',
    lede: 'Setzt eure Gäste an ihre Tische und schreibt zu jeder Person eine Zeile für die anderen am Tisch. So kommt das Gespräch von selbst.',
    table: 'Tisch',
    tablePlaceholder: 'z. B. Rigi',
    note: 'Eine Zeile für die Tischnachbarn',
    notePlaceholder: 'Annas Studienfreundin. Fragt sie nach Island.',
    publish: 'Gästen zeigen',
    publishHint:
        'Erst dann sehen eure Gäste ihren Tisch, mit Vornamen und eurer Zeile. Nachnamen und Allergien nie.',
    summary: (tables: number, seated: number, total: number) =>
        `${tables} ${tables === 1 ? 'Tisch' : 'Tische'} · ${seated} von ${total} Gästen gesetzt`,
    empty: 'Noch keine Gäste. Fügt sie unter «Gäste» hinzu.',
    save: 'Tischplan speichern',
    saved: 'Gespeichert',
};

export const wayHomePage = {
    title: 'Heimweg',
    lede: 'Das letzte Schiff, der Shuttle, der letzte Zug. Eure Gäste sehen die Zeiten am Tag selbst mit einem Countdown, und alle, die kommen und eine E-Mail-Adresse angegeben haben, bekommen eine halbe Stunde vorher eine kurze Mail.',
    label: 'Verbindung',
    labelPlaceholder: 'Letztes Schiff nach Luzern',
    place: 'Abfahrt',
    placePlaceholder: 'Steg Vitznau',
    time: 'Zeit',
    timeHint: 'Zeiten vor Mittag gelten für die Nacht nach dem Fest.',
    add: 'Verbindung hinzufügen',
    remove: 'Entfernen',
    save: 'Heimweg speichern',
    saved: 'Gespeichert',
};
