/** Copy for partner accounts (spec 2026-10-15-partner-accounts). German only. */
export const partnerCalendar = {
    title: 'Eure Hochzeiten',
    /** Venues and planners see the numbers; photographers no guest data at all. */
    lede: (seesNumbers: boolean) =>
        seesNumbers
            ? 'Jede Hochzeit, deren Paar euch mit eurem Code verbunden hat, nach Datum. Ihr seht Zahlen, Menüs und Allergien pro Tisch, keine Namen.'
            : 'Jede Hochzeit, deren Paar euch mit eurem Code verbunden hat, nach Datum, mit dem Link für eure Fotos. Gästedaten seht ihr keine.',
    codeLabel: 'Euer Code für Paare',
    codeHint:
        'Gebt euren Paaren den Code oder schickt ihnen gleich den Link: Er führt zur Anmeldung und verbindet danach mit einem Klick.',
    copy: 'Code kopieren',
    copyLink: 'Link kopieren',
    copied: 'Kopiert',
    empty: 'Noch keine Hochzeit verbunden. Sobald ein Paar euren Code einträgt, erscheint es hier.',
    households: (answered: number, total: number) =>
        `${answered} von ${total} Haushalten ${answered === 1 ? 'hat' : 'haben'} geantwortet`,
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
    lede: 'Die Fotos landen in den «Erinnerungen» des Paars. Sie gehen mit der Hochzeit, wenn das Paar sie löscht oder die Frist abläuft.',
    pick: 'Fotos auswählen',
    sending: (done: number, total: number) => `${done} von ${total} gesendet …`,
    sent: (count: number) => `${count} Fotos angekommen.`,
    failed: (count: number) =>
        `${count === 1 ? '1 Foto ist' : `${count} Fotos sind`} nicht angekommen. Möglich sind JPG, PNG und WebP bis 20 MB.`,
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
    lede: 'Eure Location, euer Fotograf, eure Planerin: verbunden sehen sie, was sie brauchen, und nie mehr. Ihr könnt das jederzeit wieder lösen.',
    roles: {
        venue: {
            label: 'Location',
            sees: 'Sieht Gästezahlen, Menüs und Allergien pro Tisch. Füllt Adresse, Anreise und Zimmer auf eurer Seite aus.',
        },
        photographer: {
            label: 'Fotograf:in',
            sees: 'Legt die Fotos direkt in eure «Erinnerungen». Sieht keine Gästedaten.',
        },
        planner: {
            label: 'Planer:in',
            sees: 'Sieht die Schlussliste wie die Location, keine Kontaktdaten eurer Gäste.',
        },
    },
    empty: 'Noch niemand verbunden.',
    invite: 'Einladen',
    since: (date: string) => `Verbunden seit ${date}`,
    invited: 'Eingeladen',
    withdraw: 'Zurückziehen',
    remove: 'Lösen',
    removeQuestion: 'Wirklich lösen?',
    codeTitle: 'Ihr habt einen Code?',
    codeLede:
        'Partner bei Hereby haben einen Code aus acht Zeichen. Eintragen, verbinden, fertig.',
    code: 'Code',
    codePlaceholder: 'z. B. KAST-7Q2M',
    connect: 'Verbinden',
    inviteTitle: 'Noch nicht bei Hereby?',
    inviteLede:
        'Wir schicken euren Partner einen Link. Ein Passwort wählen, und eure Hochzeit ist schon verbunden. Für Partner kostenlos.',
    inviteType: 'Wer ist es?',
    inviteName: 'Name',
    inviteNamePlaceholder: 'z. B. Seehotel Kastanienbaum',
    inviteEmail: 'E-Mail',
    inviteSend: 'Einladung senden',
    suggestion: (name: string) =>
        `${name} möchte sich mit eurer Hochzeit verbinden.`,
    suggestionLede:
        'Ihr kommt über ihren Link. Verbindet ihr, sieht der Partner nur, was oben bei seiner Rolle steht.',
    notNow: 'Nicht jetzt',
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
    edit: 'Bearbeiten',
    editTitle: (name: string) => `${name} bearbeiten`,
    login: 'E-Mail für den Zugang',
    loginHint: 'Mit dieser Adresse meldet sich der Partner an.',
    contactEmail: 'E-Mail für Paare',
    phone: 'Telefon',
    website: 'Website',
    address: 'Strasse und Nummer',
    postcode: 'PLZ',
    town: 'Ort',
    showcaseHint:
        'Vorschau für Besichtigungen, öffentliche Seite und die Zeile im Footer.',
    save: 'Speichern',
    resend: 'Neuen Zugangslink senden',
    inviteLogin: 'Zugang einrichten und Link senden',
    remove: 'Interessent löschen',
    removeQuestion: 'Wirklich löschen?',
    noLogin: 'kein Zugang',
    prospect: 'Interessent, noch ohne Zugang',
    prospectHint:
        'Für die Akquise: keine Mail, kein Login. Ihr bekommt einen Demo-Link mit seiner eigenen Hochzeitsseite, 90 Tage gültig.',
    prospectEmail: 'E-Mail für die Anfrage (optional)',
    createProspect: 'Interessent anlegen',
    demoOpen: 'Demo ansehen',
    demoCopy: 'Demo-Link kopieren',
    demoCopied: 'Kopiert',
    pitchOpen: 'Mail entwerfen',
    pitchNote: 'Persönlicher Satz für die Mail',
    pitchNoteHint:
        'Nur für euch. Steht in «Mail entwerfen» als Satz, warum Hereby zu diesem Haus passt, z. B. «Da Sie im Landgasthof Hasenstrick regelmässig Hochzeiten ausrichten, könnte das eine interessante Ergänzung für Ihre Angebote sein.»',
    importTitle: 'Mehrere Interessenten auf einmal',
    importHint:
        'Eine Location pro Zeile: Name; Ort; E-Mail; Website; persönlicher Satz. Nur der Name ist Pflicht. Wer schon in der Liste steht, wird übersprungen.',
    importLabel: 'Liste',
    importSubmit: 'Interessenten anlegen',
    /**
     * The pitch, in the team's own mail app: formal «Sie», in the founder's
     * own words (2026-10-23). The venue's own sentence, if the team wrote
     * one, says why Hereby fits this house. It names the sender in full and
     * how to say no (UWG art. 3 para. 1 lit. o).
     */
    pitch: (venue: string, demo: string, note?: string | null) => ({
        subject: `${venue}: Hochzeitswebsites für Ihre Brautpaare`,
        body: [
            `Guten Tag liebes ${venue}-Team`,
            '',
            'Eine Hochzeit ist für viele Paare einer der bedeutendsten Tage ihres Lebens. Wir finden, dass sich das auch in der Art widerspiegeln sollte, wie sie ihre Hochzeit präsentieren und ihre Gäste darauf einstimmen.',
            '',
            'Mit Hereby erstellen wir hochwertige, individuell gestaltete Hochzeitswebsites, die genau das ermöglichen. Die Paare können ihren Gästen einen ersten Eindruck von ihrer Hochzeit vermitteln und alle wichtigen Informationen stilvoll an einem Ort teilen. Ergänzt wird das Ganze durch praktische Funktionen, die auch die Organisation erleichtern.',
            '',
            note ||
                'Da bei Ihnen regelmässig Hochzeiten gefeiert werden, könnte das eine interessante Ergänzung für Ihr Angebot sein.',
            '',
            'Um Ihnen einen konkreten Eindruck zu vermitteln, habe ich eine Beispielwebsite speziell für Ihr Haus erstellt:',
            '',
            demo,
            '',
            'Vielleicht passt unser Ansatz auch zu Ihrem Betrieb. Gerne zeige ich Ihnen Hereby bei Interesse persönlich vor Ort oder kurz per Video.',
            '',
            'Falls das für Sie momentan nicht interessant ist, ist das natürlich völlig in Ordnung. Über eine kurze Rückmeldung freue ich mich in jedem Fall. Wünschen Sie keine weiteren Nachrichten von mir, genügt eine kurze Antwort.',
            '',
            'Freundliche Grüsse',
            '',
            'Martin Brunner',
            'Hereby · hereby.ch · info@hereby.ch',
            'Datenschutz: hereby.ch/datenschutz',
        ].join('\n'),
    }),
};

/** The contact and street fields in the team's «Bearbeiten», in order. */
export const adminPartnerFields = [
    { key: 'email', label: adminPartners.contactEmail, type: 'email' },
    { key: 'phone', label: adminPartners.phone, type: 'tel' },
    { key: 'website', label: adminPartners.website, type: 'url' },
    { key: 'address', label: adminPartners.address, type: 'text' },
    { key: 'postcode', label: adminPartners.postcode, type: 'text' },
    { key: 'town', label: adminPartners.town, type: 'text' },
] as const;

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
    thanks: (venue: string) => `Danke! ${venue} meldet sich bei euch.`,
    thanksLede: 'Eure Anfrage ist angekommen. Die Antwort kommt per E-Mail.',
    privacy:
        'Eure Angaben gehen nur an diese Location und werden nach 12 Monaten gelöscht.',
};

export const venuePhotos = {
    title: 'Fotos',
    hint: 'Bis zu sechs. Das erste steht oben auf eurer Seite und in jeder Vorschau für Besichtigungen.',
    add: (room: number) => `Fotos hinzufügen (noch ${room})`,
    uploading: 'Lädt hoch …',
    remove: 'Foto löschen',
};
