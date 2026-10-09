/** Copy for the team's admin (Swiss German). Roadmap 1.1. */
export const adminCopy = {
    title: 'Hochzeiten',
    lede: 'Alle Hochzeiten auf Hereby. Öffnet eine, um sie mit den Seiten des Paars einzurichten.',
    listTitle: 'Alle Hochzeiten',
    stats: {
        weddings: 'Hochzeiten',
        drafts: 'Im Entwurf',
        active: 'Aktiv',
        answered: 'Antworten',
    },
    newCouple: 'Hochzeit für ein Paar anlegen',
    newCoupleHint:
        'Legt Konto und Entwurf an. Das Paar bekommt einen Link, um sein Passwort zu wählen.',
    email: 'E-Mail des Paars',
    partnerOne: 'Vorname, Person 1',
    partnerTwo: 'Vorname, Person 2',
    partnerOnePlaceholder: 'Anna',
    partnerTwoPlaceholder: 'Luca',
    emailPlaceholder: 'anna.luca@beispiel.ch',
    create: 'Anlegen und einladen',
    draft: (step: string) => `Entwurf, bei «${step}»`,
    active: 'Aktiv',
    households: (count: number) =>
        count === 1 ? '1 Haushalt' : `${count} Haushalte`,
    answered: (count: number) => `${count} geantwortet`,
    open: 'Einrichtung',
    guests: 'Gäste',
    content: 'Inhalte',
    preview: 'Vorschau',
    empty: 'Noch keine Hochzeiten.',
    emptyHint:
        'Sobald ihr ein Paar anlegt, erscheint es hier, und das Paar bekommt sofort seinen Link.',
    draftBadge: 'Entwurf',
};

/** Copy for the team's inbox of landing-page questions. */
export const inquiriesCopy = {
    title: 'Anfragen',
    lede: 'Fragen, die Paare auf der Startseite gestellt haben. Antwortet per E-Mail und hakt sie hier ab.',
    open: 'Offen',
    answered: 'Beantwortet',
    reply: 'Per E-Mail antworten',
    replySubject: 'Eure Frage an Hereby',
    markAnswered: 'Als beantwortet markieren',
    reopen: 'Wieder öffnen',
    emptyOpen: 'Alles beantwortet. Schön.',
    emptyAnswered: 'Noch nichts beantwortet.',
};

/** First-visit tour of the team area; photos from content/landing-photos. */
export const adminWelcome = {
    open: 'Rundgang',
    next: 'Weiter',
    back: 'Zurück',
    done: 'Los geht’s',
    skip: 'Überspringen',
    slides: [
        {
            photo: 'tableCandles',
            title: 'Willkommen im Team-Bereich',
            text: 'Hier legt ihr Hochzeiten an, begleitet Paare durch die Einrichtung und behaltet jede Website im Blick.',
        },
        {
            photo: 'bouquet',
            title: 'Ein Paar anlegen',
            text: 'Zwei Vornamen und eine E-Mail genügen. Das Paar bekommt einen Link, wählt sein Passwort, und ihr richtet die Seite gemeinsam ein.',
        },
        {
            photo: 'lakeJetty',
            title: 'Anfragen im Blick',
            text: 'Fragen von der Startseite landen unter «Anfragen». Die Zahl in der Seitenleiste zeigt, was noch offen ist.',
        },
    ],
} as const;

/** The admin's starter checklist; each step is derived from live data. */
export const adminChecklist = {
    title: 'Erste Schritte',
    progress: (done: number, total: number) => `${done} von ${total} erledigt`,
    hide: 'Ausblenden',
    show: 'Erste Schritte zeigen',
    steps: {
        couple: 'Das erste Paar anlegen',
        setup: 'Eine Einrichtung mit dem Paar abschliessen',
        guests: 'Gäste für eine Hochzeit importieren',
        reply: 'Die erste Antwort eines Gastes erhalten',
        inbox: 'Alle Anfragen beantworten',
    },
};
