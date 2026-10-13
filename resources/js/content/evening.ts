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
    },
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
