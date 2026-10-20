import type { TourCopyStep } from '@/content/tour';

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
    newCoupleShort: 'Paar anlegen',
    columns: {
        couple: 'Paar',
        status: 'Stand',
        date: 'Hochzeit',
        replies: 'Antworten',
    },
    noDate: 'Datum offen',
    linksClosed: 'Links zu',
    unlock: 'Bezahlt per Rechnung',
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

/** First-visit tour of the team area: the real controls, one by one. */
export const adminTour: TourCopyStep[] = [
    {
        target: null,
        photo: 'tableCandles',
        title: 'Willkommen im Team-Bereich',
        text: 'Hier legt ihr Hochzeiten an, begleitet Paare durch die Einrichtung und behaltet jede Website im Blick. In einer Minute zeigen wir euch, wo alles ist.',
    },
    {
        target: 'create',
        title: 'Ein Paar anlegen',
        text: 'Zwei Vornamen und eine E-Mail genügen. Das Paar bekommt einen Link, wählt sein Passwort, und ihr richtet die Seite gemeinsam ein.',
        interactive: true,
    },
    {
        target: 'stats',
        title: 'Die Zahlen',
        text: 'Alle Hochzeiten, Entwürfe, aktive Seiten und die Antworten der Gäste, immer auf dem neusten Stand.',
    },
    {
        target: 'checklist',
        title: 'Erste Schritte',
        text: 'Die Liste hakt sich selbst ab, sobald ihr etwas erledigt habt. Wenn alles steht, verschwindet sie.',
    },
    {
        target: 'view',
        title: 'Team oder Paar',
        text: 'In der Paar-Ansicht seht und bearbeitet ihr eine Hochzeit so, wie das Paar sie sieht. Hier wechselt ihr zurück.',
        interactive: true,
    },
    {
        target: 'inquiries',
        title: 'Anfragen',
        text: 'Fragen von der Startseite landen hier. Die Zahl daneben zeigt, was noch offen ist.',
        interactive: true,
    },
    {
        target: 'chase',
        title: 'Nachfassen',
        text: 'Für Paare mit «Wir fassen nach»: Haushalte ohne Antwort, kurz bevor die Frist endet.',
        interactive: true,
    },
    {
        target: 'partners',
        title: 'Partner',
        text: 'Locations, Fotografen und Planer. Hier legt ihr auch Interessenten an und schickt ihnen ihre eigene Demo.',
        interactive: true,
    },
    {
        target: 'log',
        title: 'Protokoll',
        text: 'Warnungen und Fehler der Website. Bei einem grossen Fehler bekommt ihr zusätzlich eine Mail.',
        interactive: true,
    },
    {
        target: 'weddings',
        title: 'Alle Hochzeiten',
        text: 'Jede Hochzeit mit Stand, Datum und Antworten. Ein Klick auf eine Zeile öffnet sie.',
    },
];

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

export const chaseCopy = {
    title: 'Nachfassen',
    lede: 'Paare mit «Wir fassen nach», deren Frist in den nächsten zwei Wochen endet: alle Haushalte ohne Antwort. Ruft an oder schreibt, dann hakt sie ab.',
    empty: 'Gerade niemand, dem wir nachfassen müssten.',
    deadline: (date: string) =>
        `Frist ${new Date(date).toLocaleDateString('de-CH', { dateStyle: 'medium' })}`,
    opened: 'Geöffnet, nicht geantwortet',
    unopened: 'Nie geöffnet',
    noContact: 'Keine Kontaktdaten. Fragt das Paar.',
    markChased: 'Erledigt',
    chased: 'Kontaktiert',
};
