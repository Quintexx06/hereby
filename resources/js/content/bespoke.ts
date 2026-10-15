/** «Massanfertigung» (spec 2026-10-15-bespoke-and-design-freedom). */
export const bespoke = {
    title: 'Massanfertigung',
    lede: 'Ihr sagt uns, wie eure Seite sein soll: Farben, Stimmung, Fotos, Vorbilder. Wir bauen sie für euch, von Hand, auf einer der drei Vorlagen und darüber hinaus.',
    steps: [
        {
            title: 'Ihr erzählt',
            body: 'Wünsche, Farben, Bilder und Links, die euch gefallen. Alles hier auf dieser Seite.',
        },
        {
            title: 'Wir bauen',
            body: 'Innert zwei Arbeitstagen melden wir uns, dann richten wir eure Seite ein: Look, Texte, Fotos, Abläufe.',
        },
        {
            title: 'Ihr gebt frei',
            body: 'Ihr seht alles in der Vorschau, wünscht euch Änderungen, und erst dann geht sie an eure Gäste.',
        },
    ],
    price: (chf: number) => `CHF ${chf} einmalig`,
    book: 'Massanfertigung buchen',
    needsPlan: 'Zuerst ein Paket wählen',
    included: 'In eurem Paket enthalten',
    owned: 'Gebucht',
    paymentsOff: 'Zahlungen sind noch nicht eingerichtet. Schreibt uns.',
    form: {
        title: 'Euer Briefing',
        wishes: 'Eure Wünsche',
        wishesHint:
            'Was soll eure Seite ausstrahlen? Was muss unbedingt drauf, was auf keinen Fall?',
        colors: 'Eure Farben',
        colorsHint:
            'Bis zu drei. Wir stimmen sie so ab, dass sie gut lesbar bleiben.',
        addColor: 'Farbe hinzufügen',
        removeColor: 'Farbe entfernen',
        mood: 'Stimmung in ein paar Worten',
        moodPlaceholder:
            'z. B. Sommerfest am See, leicht, viel Weiss, etwas Gold',
        links: 'Vorbilder und Links',
        linksHint:
            'Pinterest, Instagram, Seiten, die euch gefallen. Eine pro Zeile.',
        readyBy: 'Fertig bis (optional)',
        save: 'Speichern',
        saved: 'Gespeichert',
        submit: 'An das Team senden',
        submitHint:
            'Ihr könnt danach weiter ergänzen; wir sehen jede Änderung.',
    },
    photos: {
        title: 'Bilder, die euch gefallen',
        hint: 'Bis zu zwölf: Kleider, Blumen, Orte, Seiten. Nur ihr und unser Team sehen sie.',
        add: (room: number) => `Bilder hinzufügen (noch ${room})`,
        uploading: 'Lädt hoch …',
        remove: 'Bild löschen',
    },
    status: {
        submitted: 'Eingegangen. Wir melden uns innert zwei Arbeitstagen.',
        in_progress: 'Wir bauen eure Seite.',
        ready: 'Fertig. Schaut sie euch in der Vorschau an.',
    } as Record<string, string>,
    teamNote: 'Nachricht vom Team',
    openPreview: 'Vorschau öffnen',
};

export const adminBriefs = {
    title: 'Massanfertigung',
    lede: 'Briefings der Paare, Neues zuerst. Baut die Seite auf den Seiten des Paars (Eckdaten, Inhalte, Look) und setzt den Status; das Paar sieht ihn und eure Nachricht.',
    empty: 'Noch kein Briefing eingegangen.',
    readyBy: (date: string) => `fertig bis ${date}`,
    open: 'Seiten des Paars öffnen',
    note: 'Nachricht an das Paar',
    save: 'Speichern',
    wishes: 'Wünsche',
    mood: 'Stimmung',
    links: 'Links',
};
