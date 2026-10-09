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
    shuttleHint: 'Jeder Haushalt sagt, wie viele Plätze er braucht.',
    stayHint: 'Damit ihr Zimmer reservieren könnt.',
    songHint: 'Ein Lied pro Haushalt für eure Playlist.',
    save: 'Speichern',
    preview: 'So sieht es für eure Gäste aus',
    reminders: 'Erinnerungen',
    remindersToggle: 'Erinnerungen automatisch senden',
    remindersHint:
        'An Haushalte mit E-Mail-Adresse, die noch nicht geantwortet haben: 14, 7 und 2 Tage vor der Frist, mit ihrem persönlichen Link und euren Namen als Absender. Abmelden geht mit einem Klick.',
    remindersNext: (dates: string) => `Nächste Erinnerungen: ${dates}.`,
    remindersNoDeadline:
        'Legt in der Einrichtung eine Antwortfrist fest, dann laufen die Erinnerungen von selbst.',
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
