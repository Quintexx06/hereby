/** Copy for the couple's "Antwortformular" (Swiss German, "ihr"). */
export const rsvpSettings = {
    title: 'Antwortformular',
    lede: 'Was eure Gäste gefragt werden, wenn sie antworten. Was ihr nicht braucht, bleibt weg, damit eine Antwort unter einer Minute dauert.',
    always: 'Immer dabei',
    alwaysItems: [
        'Zusage oder Absage, pro Person und Teil des Tages',
        'Allergien und Unverträglichkeiten (freiwillig, nur für euch und die Küche)',
        'Eine Begleitung mit Namen, wo ihr sie im Haushalt erlaubt',
    ],
    menus: 'Menüs am Abendessen',
    menusHint:
        'Bis vier Menüs, in euren Worten. Leer lassen, wenn es keine Wahl gibt.',
    menuLabel: (index: number) => `Menü ${index + 1}`,
    menuPlaceholder: 'z. B. Kalbsfilet, Kartoffelgratin',
    addMenu: 'Menü hinzufügen',
    removeMenu: 'Menü entfernen',
    childrenMenu: 'Kindermenü anbieten',
    childrenMenuHint: 'Kinder sehen es zuerst in ihrer Auswahl.',
    household: 'Fragen pro Haushalt',
    shuttle: 'Shuttle: wie viele Plätze?',
    stay: 'Übernachtung vor Ort: ja oder nein?',
    song: 'Liederwunsch',
    save: 'Speichern',
    preview: 'So sieht es für eure Gäste aus',
};

/** The guest-side words, for the preview only (German). */
export const rsvpPreview = {
    title: 'Kommt ihr?',
    person: 'Heidi',
    attending: 'Dabei',
    declined: 'Leider nicht',
    allergies: 'Allergien oder Unverträglichkeiten?',
    shuttle: 'Plätze im Shuttle',
    stay: 'Übernachtet ihr vor Ort?',
    song: 'Euer Liederwunsch',
    send: 'Antwort senden',
};
