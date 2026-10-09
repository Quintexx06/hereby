/** Copy for the couple's dashboard (Swiss German, "ihr"). */
export const start = {
    title: 'Willkommen bei Hereby.',
    lede: 'Sieben kurze Fragen, dann steht eure Hochzeitswebsite. Rund zehn Minuten, und ihr könnt jederzeit pausieren.',
    cta: 'Website einrichten',
    steps: [
        'Ihr erzählt uns von eurem Tag: Datum, Ort, Ablauf.',
        'Ihr wählt einen Look und seht eure Einladung sofort.',
        'Ihr ladet eure Gästeliste hoch, und jeder Haushalt bekommt seinen Link.',
    ],
};

export const draft = {
    title: 'Fast geschafft.',
    lede: (step: string) =>
        `Ihr seid bei «${step}». Alles bisher ist gespeichert.`,
    cta: 'Weiter einrichten',
};

export const active = {
    today: 'Heute für euch',
    nothingToday: 'Alles erledigt. Geniesst den Abend.',
    replies: 'Antworten',
    programme: 'Euer Tag',
    invited: (count: number) =>
        count === 1 ? '1 Haushalt eingeladen' : `${count} Haushalte eingeladen`,
    attending: (count: number) => `${count} zugesagt`,
    toGuests: 'Zur Gästeliste',
    noRepliesYet:
        'Noch keine Antworten. Sobald ihr die Links teilt, seht ihr hier, wer kommt.',
    daysLeft: (days: number) =>
        days > 1
            ? `noch ${days} Tage`
            : days === 1
              ? 'morgen'
              : days === 0
                ? 'heute'
                : 'gefeiert',
    counts: (households: number, guests: number) =>
        `${households} ${households === 1 ? 'Haushalt' : 'Haushalte'}, ${guests} ${guests === 1 ? 'Gast' : 'Gäste'}`,
};

export const replyStatus = {
    never_opened: {
        label: 'Noch nicht geöffnet',
        hint: 'Teilt ihren Link noch einmal.',
    },
    opened: {
        label: 'Geöffnet, ohne Antwort',
        hint: 'Eine freundliche Erinnerung hilft.',
    },
    answered: { label: 'Geantwortet', hint: 'Ihre Zusage oder Absage ist da.' },
} as const;

/** "Heute für euch": what each computed action asks of the couple. */
export const todayActions: Record<
    string,
    { title: string; body: (count?: number) => string; cta: string }
> = {
    import_guests: {
        title: 'Gästeliste hinzufügen',
        body: () =>
            'Aus Excel, einer Liste oder euren Kontakten. Wir zeigen euch alles, bevor etwas gespeichert wird.',
        cta: 'Gäste hinzufügen',
    },
    share_links: {
        title: 'Links teilen',
        body: (count = 0) =>
            count === 1
                ? '1 Haushalt hat seine Einladung noch nicht geöffnet.'
                : `${count} Haushalte haben ihre Einladung noch nicht geöffnet.`,
        cta: 'Links ansehen',
    },
    nudge_opened: {
        title: 'Erinnern',
        body: (count = 0) =>
            `${count} Haushalte haben geöffnet, aber noch nicht geantwortet. Die Frist rückt näher.`,
        cta: 'Haushalte ansehen',
    },
    set_deadline: {
        title: 'Antwortfrist festlegen',
        body: () =>
            'Ohne Frist wissen Küche und Service nicht, wann die Zahlen fix sind.',
        cta: 'Frist festlegen',
    },
};

export const invitationPanel = {
    title: 'Eure Einladung',
    theme: (name: string) => `Im Look «${name}»`,
    change: 'Look ändern',
};

export const dashboardPage = { title: 'Übersicht' };

export const sidebarWedding = {
    fallbackName: 'Eure Hochzeit',
    inSetup: (step: string) => `Einrichtung: ${step}`,
};
