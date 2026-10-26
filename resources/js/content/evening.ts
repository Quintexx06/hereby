/** Copy for «Der Abend», the couple's side (spec 2026-10-13). */

export const eveningPage = {
    title: 'Der Abend',
    lede: 'Wer wo sitzt, wer wann spricht, wer auf welches Foto gehört und wie alle heimkommen. Damit der Abend von selbst läuft.',
    locked: 'Der Abend ist Teil von Atelier und Couture. Eure Gäste sehen dann ihren Tisch, ihren Heimweg und den Aufruf zum Gruppenfoto.',
    unlock: 'Pakete ansehen',
    open: 'Öffnen',
    tools: 'Für den Tag',
    sections: {
        seating: {
            title: 'Tischplan',
            body: 'Wer neben wem sitzt, mit einer Zeile zu jeder Person.',
        },
        programme: {
            title: 'Einlagen',
            body: 'Reden, Spiele und Lieder eurer Gäste. Die Trauzeugen stimmen sie über ihren eigenen Link ab.',
        },
        groupPhotos: {
            title: 'Gruppenfotos',
            body: 'Wer auf welches Foto gehört. Die Trauzeugen rufen die Gruppen am Tag auf.',
        },
        newspaper: {
            title: 'Hochzeitszeitung',
            body: 'Geschichten und Fotos eurer Freunde, gedruckt von den Trauzeugen. Ein Extra.',
        },
        wayHome: {
            title: 'Heimweg',
            body: 'Die letzten Verbindungen der Nacht. Eure Gäste bekommen eine halbe Stunde vorher eine Mail.',
        },
    },
    programmeStatus: (s: { total: number; accepted: number }) =>
        s.total === 0
            ? 'Noch keine Einlagen'
            : `${s.total} angemeldet · ${s.accepted} im Programm`,
    groupPhotosStatus: (count: number) =>
        count === 0
            ? 'Noch keine Gruppen'
            : `${count} ${count === 1 ? 'Gruppe' : 'Gruppen'}`,
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

export const programmePage = {
    title: 'Einlagen',
    lede: 'Eure Gäste melden Reden, Spiele und Lieder über ihre persönliche Einladung an, nachdem sie geantwortet haben. Was geplant ist, bleibt eine Überraschung: Das entscheiden eure Trauzeugen über ihren eigenen Link. Ihr seht nur, wie viele es sind.',
    linkLabel: 'Link für eure Trauzeugen',
    linkHint:
        'Ohne Konto und privat. Gebt ihn nur euren Trauzeugen; dort entscheiden sie über die Einlagen und drucken den Ablauf.',
    renew: 'Neuen Link erstellen',
    renewHint: 'Der alte Link funktioniert danach nicht mehr.',
    renewConfirm: 'Den alten Link ungültig machen?',
    summary: (s: { total: number; accepted: number; minutes: number }) =>
        `${s.total} angemeldet · ${s.accepted} im Programm · ${s.minutes} Minuten`,
    budget: 'Zeitrahmen für die Einlagen, in Minuten',
    budgetHint:
        'Die Trauzeugen sehen, wie viel davon schon verplant ist. Leer lassen für keinen Rahmen.',
    save: 'Zeitrahmen speichern',
    saved: 'Gespeichert',
};

export const groupPhotosPage = {
    title: 'Gruppenfotos',
    lede: 'Listet eure Gruppenfotos und wählt, wer auf welches gehört. Am Tag tippen eure Trauzeugen auf «Aufrufen»: Die Gäste bekommen eine Mail und sehen es auf ihrer Einladung. Niemand muss mehr durch den Saal rufen.',
    title_: 'Foto',
    titlePlaceholder: 'Familie Meier',
    place: 'Treffpunkt',
    placePlaceholder: 'Beim Steg',
    who: (count: number) =>
        count === 0
            ? 'Haushalte wählen'
            : `${count} ${count === 1 ? 'Haushalt' : 'Haushalte'}`,
    add: 'Gruppenfoto hinzufügen',
    remove: 'Entfernen',
    empty: 'Noch keine Gäste. Fügt sie unter «Gäste» hinzu.',
    save: 'Gruppenfotos speichern',
    saved: 'Gespeichert',
};
