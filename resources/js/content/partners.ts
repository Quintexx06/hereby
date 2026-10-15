/** Copy for partner accounts (spec 2026-10-15-partner-accounts). German only. */
export const partnerCalendar = {
    title: 'Eure Hochzeiten',
    lede: 'Jede Hochzeit, deren Paar euch mit eurem Code verbunden hat, nach Datum. Ihr seht Zahlen und Menüs, keine Namen.',
    codeLabel: 'Euer Code für Paare',
    codeHint:
        'Gebt ihn euren Paaren. Sie tragen ihn bei Hereby unter «Partner» ein; erst dann seht ihr ihre Hochzeit.',
    copy: 'Kopieren',
    copied: 'Kopiert',
    empty: 'Noch keine Hochzeit verbunden. Sobald ein Paar euren Code einträgt, erscheint es hier.',
    households: (answered: number, total: number) =>
        `${answered} von ${total} Haushalten haben geantwortet`,
    attending: (count: number) => `${count} Gäste kommen`,
    open: (date: string) => `Antworten offen bis ${date}`,
    closed: 'Antworten geschlossen',
    sheet: 'Schlussliste drucken',
    upload: 'Fotos hochladen',
    noUpload: 'Fotos erst mit dem Paket Atelier',
    enquiries: 'Anfragen für Besichtigungen',
    enquiriesEmpty: 'Noch keine Anfrage über eure Seite.',
    publicPage: 'Eure öffentliche Seite',
    wishDate: (date: string) => `Wunschtermin ${date}`,
};

export const partnerProfile = {
    title: 'Profil',
    lede: 'Einmal eingetragen, für jedes Paar richtig. Bei Locations füllen Adresse, Anreise und Zimmer die Hochzeitsseite jedes Paars, das euch verbindet, wo es selbst noch nichts geschrieben hat.',
    fields: {
        name: 'Name',
        email: 'E-Mail',
        phone: 'Telefon',
        website: 'Website',
        address: 'Strasse und Nummer',
        postcode: 'PLZ',
        town: 'Ort',
        capacity: 'Gäste (höchstens)',
        about: 'Über euch',
        travel: 'Anreise',
        stay: 'Übernachten',
    },
    hints: {
        about: 'Ein paar Sätze für eure öffentliche Seite und den Ort-Block.',
        travel: 'Zug, Bus, Schiff, Parkplätze: so wie ihr es Gästen erklären würdet.',
        stay: 'Zimmer im Haus und in der Nähe.',
    },
    save: 'Speichern',
    saved: 'Gespeichert',
};

export const partnerUpload = {
    title: (couple: string) => `Fotos für ${couple}`,
    lede: 'Die Fotos landen in den «Erinnerungen» des Paars, als eure markiert. Sie gehen mit der Hochzeit, wenn das Paar sie löscht oder die Frist abläuft.',
    pick: 'Fotos auswählen',
    sending: (done: number, total: number) => `${done} von ${total} gesendet …`,
    sent: (count: number) => `${count} Fotos angekommen.`,
    failed: (count: number) => `${count} Fotos sind nicht angekommen.`,
    retry: 'Nochmals senden',
    already: (count: number) => `Bisher ${count} Fotos von euch.`,
};

export const tourPreview = {
    title: 'Vorschau für Besichtigungen',
    lede: 'Zwei Namen und ein Datum, und das Paar sieht seine Hochzeitsseite bei euch: mit eurer Adresse, Anreise und euren Zimmern. Es wird nichts gespeichert.',
    first: 'Vorname',
    second: 'Vorname',
    date: 'Datum',
    template: 'Vorlage',
    show: 'Seite zeigen',
    handover: 'Gefällt sie? Gebt dem Paar euren Code:',
};

export const weddingPartners = {
    title: 'Partner',
    lede: 'Verbindet eure Location, euren Fotografen oder eure Planerin mit ihrem Code. Ihr könnt das jederzeit wieder lösen.',
    code: 'Code',
    codePlaceholder: 'z. B. KAST-7Q2M',
    connect: 'Verbinden',
    empty: 'Noch niemand verbunden.',
    seesNumbers:
        'Sieht Zahlen, Menüs und Allergien pro Tisch (die Schlussliste), keine Kontaktdaten.',
    seesPhotos:
        'Kann Fotos in eure «Erinnerungen» hochladen. Sieht keine Gästedaten.',
    remove: 'Lösen',
    removeQuestion: 'Wirklich lösen?',
};

export const adminPartners = {
    title: 'Partner',
    lede: 'Locations, Fotografen und Planer. Legt einen Zugang an; die Person wählt ihr Passwort per Mail. Showcase schaltet Vorschau, öffentliche Seite und die Zeile im Footer frei.',
    create: 'Partner anlegen',
    type: 'Art',
    name: 'Name',
    email: 'E-Mail für den Zugang',
    empty: 'Noch keine Partner.',
    weddings: (count: number) =>
        count === 1 ? '1 Hochzeit' : `${count} Hochzeiten`,
    showcase: 'Showcase',
};

export const venuePage = {
    capacity: (count: number) => `Bis ${count} Gäste`,
    travel: 'Anreise',
    stay: 'Übernachten',
    website: 'Website',
    ask: 'Besichtigung anfragen',
    askLede:
        'Ihr heiratet bald? Schreibt der Location direkt, sie meldet sich bei euch.',
    name: 'Eure Namen',
    email: 'E-Mail',
    date: 'Wunschtermin (optional)',
    message: 'Nachricht (optional)',
    send: 'Anfrage senden',
    privacy:
        'Eure Angaben gehen nur an diese Location und werden nach 12 Monaten gelöscht.',
};
