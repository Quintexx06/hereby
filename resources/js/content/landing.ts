/**
 * Landing page copy, Swiss German (no "ß"), addressed to the couple as "ihr".
 * Keyword research, message hierarchy and UX rationale:
 * docs/marketing/landing-page.md. SEO strings and the FAQ live in
 * lang/de_CH/landing.php because the server renders them too.
 */
import { landingPhotos as photo } from '@/content/landing-photos';

export const nav = [
    { label: 'Vorlagen', href: '/#studio' },
    { label: 'Für Gäste', href: '/#gaeste' },
    { label: 'Ablauf', href: '/#ablauf' },
    { label: 'Der Tag', href: '/#tag' },
    { label: 'Preise', href: '/#preise' },
    { label: 'Fragen', href: '/#fragen' },
] as const;

export const hero = {
    titleLead: 'Vorhang auf',
    titleTail: 'für euer',
    titleYes: 'Ja.',
    statement:
        'Premium-Hochzeitswebsites aus der Schweiz. Gestaltet wie ein Magazin, persönlich für jeden Gast.',
    lede: 'Fünf fertig gestaltete Entwürfe, gefüllt mit euren Fotos und Texten. Jeder Haushalt bekommt seinen eigenen Link und sagt in unter einer Minute zu.',
    primaryCta: 'Kostenlos starten',
    secondaryCta: 'Vorlagen ansehen',
    /* Ends the lede: «… Gestalten ist gratis, online ab CHF 290, einmalig.» */
    priceLead: 'Gestalten ist gratis, online ab',
    priceTail: ', einmalig.',
    skip: 'Vorhang öffnen',
    photo: photo.heroVeil,
} as const;

export const intro = {
    title: 'Eure Gäste bekommen keine Website. Sie bekommen ihre Einladung.',
    body: 'Eine gewöhnliche Hochzeitshomepage zeigt allen dasselbe: das Abendessen, zu dem nicht alle kommen, ein Formular voller Fragen, die nicht passen, und ein Passwort, das niemand findet. Bei Hereby öffnet jeder Haushalt seine eigene Einladung. Er sieht nur seine Anlässe, antwortet in seiner Sprache und muss sich nirgends anmelden.',
} as const;

export const guests = {
    title: 'Ein Link pro Haushalt. In seiner Sprache.',
    lede: 'Die Familie aus Lausanne liest Französisch, die Nonna im Tessin Italienisch, die Freunde aus London Englisch. Und alle sehen nur, wozu sie eingeladen sind.',
    points: [
        {
            icon: 'link',
            title: 'Kein Passwort, keine App',
            body: 'Ein privater Link genügt, per WhatsApp, SMS oder Mail. Suchmaschinen sehen ihn nie.',
        },
        {
            icon: 'events',
            title: 'Nur, was gilt',
            body: 'Ziviltrauung, Apéro, Abendessen, Fest: Jeder Haushalt sieht seine Anlässe.',
        },
        {
            icon: 'clock',
            title: 'Antwort in unter 60 Sekunden',
            body: 'Namen sind ausgefüllt. Gefragt wird nur, was für diesen Haushalt zählt: Menü, Allergien, Kinder.',
        },
    ],
    previewLabel:
        'Beispiel: dieselbe Einladung für drei Haushalte, auf Deutsch, Französisch und Englisch',
    photo: photo.veilKiss,
} as const;

export const steps = {
    title: 'In drei Schritten zur eigenen Hochzeitswebsite.',
    items: [
        {
            figure: '10',
            unit: 'Min.',
            title: 'Einrichten',
            body: 'Namen, Datum, Ort, Ablauf, Sprachen und Look: sieben kurze Fragen, und eure Seite steht. Kostenlos, ohne Kreditkarte.',
        },
        {
            figure: '1',
            unit: 'Link',
            title: 'Pro Haushalt verschicken',
            body: 'Gästeliste aus Excel oder den Kontakten übernehmen, Paket wählen, Links per WhatsApp oder Mail teilen.',
        },
        {
            figure: '60',
            unit: 'Sek.',
            title: 'Gäste antworten',
            body: 'Auf dem Handy, ohne Konto und ohne App. Ihr seht jede Zusage sofort, mit Menüs und Allergien für die Küche.',
        },
    ],
} as const;

export const acts = {
    title: 'Ein Tag in fünf Akten.',
    lede: 'Jeder Anlass hat seine Gästeliste, seinen Ort, seine Anreise. Jeder Haushalt sieht nur die Akte, zu denen er eingeladen ist.',
    example: 'Beispiel: die Hochzeit von Anna & Luca',
    items: [
        {
            numeral: 'I',
            name: 'Ziviltrauung',
            when: 'Freitag, 14:00',
            place: 'Stadthaus Luzern',
            guests: 'Nur die Familie',
            note: 'Die Familie sieht diesen Akt. Alle anderen erfahren nicht einmal davon.',
            photo: photo.bouquet,
        },
        {
            numeral: 'II',
            name: 'Apéro am See',
            when: 'Samstag, 15:30',
            place: 'Seeterrasse, Vitznau',
            guests: 'Alle Gäste',
            note: 'Die Location ist schon drin: Schiff, Shuttle, Parkplätze und Zimmer, für jeden Gast übersichtlich.',
            photo: photo.lakeJetty,
        },
        {
            numeral: 'III',
            name: 'Abendessen',
            when: 'Samstag, 18:30',
            place: 'Grand Salon',
            guests: 'Ein Teil der Gäste',
            note: 'Menüs, Kinder und Allergien pro Tisch gehen auf Knopfdruck an die Küche.',
            photo: photo.tableCandles,
        },
        {
            numeral: 'IV',
            name: 'Fest',
            when: 'Samstag, 22:00',
            place: 'Bootshaus',
            guests: 'Alle Gäste',
            note: 'Wer nur zum Fest kommt, sieht nur das Fest, mit Anreise und Heimweg.',
            photo: photo.sparklers,
        },
        {
            numeral: 'V',
            name: 'Brunch',
            when: 'Sonntag, 11:00',
            place: 'Hotel am See',
            guests: 'Wer übernachtet',
            note: 'Für die Gäste mit Zimmer. Eingeladen, ohne dass jemand anderes fragt, warum nicht er.',
            photo: photo.veilKiss,
        },
    ],
} as const;

export const marquee = [
    'Hochzeit',
    'Mariage',
    'Matrimonio',
    'Wedding',
    'Ziviltrauung',
    'Apéro',
    'Fest',
    'Brunch',
] as const;

export const studio = {
    title: 'Gestaltet, nicht zusammengeklickt.',
    lede: 'Fünf Entwürfe, fertig gestaltet. Ihr bringt die Fotos.',
    live: 'Live ansehen',
    designsLabel: 'Entwürfe',
    previous: 'Vorheriger Entwurf',
    next: 'Nächster Entwurf',
    url: 'anna-und-luca.hereby.ch',
    shot: (name: string) =>
        `Erste Seite des Entwurfs ${name} mit den Beispielnamen Anna und Luca, am Computer und auf dem Handy`,
} as const;

/**
 * The paid side: only what ships today (rule 5), with the prices checkout
 * charges. Two products, told apart: the Atelier evening as moments of
 * the night, and the extras as tickets to add one by one.
 */
export const premium = {
    titleLead: 'Und dann',
    titleAccent: 'der Abend.',
    lede: 'Die Einladung ist gratis gebaut. Mit Atelier geht eure Seite weiter: Sie führt eure Gäste durch den Tag, gibt den Trauzeugen das Programm in die Hand und bringt alle sicher heim.',
    package: {
        badge: (price: string) => `Paket Atelier, ${price}`,
        title: 'Der Abend läuft von selbst',
        hint: 'Tippt auf einen Moment, um zu sehen, was eure Gäste dann auf dem Handy sehen.',
        cta: 'In Atelier und Couture',
    },
    moments: [
        {
            key: 'groupPhoto',
            time: '17:30',
            icon: 'megaphone',
            name: 'Gruppenfotos',
            line: 'Die Trauzeugen rufen eine Gruppe auf, und genau diese Gäste bekommen Bescheid.',
        },
        {
            key: 'seating',
            time: '18:30',
            icon: 'dinner',
            name: 'Tischplan',
            line: 'Jeder Gast sieht seinen Tisch und wer neben ihm sitzt, mit einer Zeile von euch.',
        },
        {
            key: 'programme',
            time: '21:00',
            icon: 'music',
            name: 'Einlagen',
            line: 'Reden, Spiele, Lieder: Gäste melden sich an, die Trauzeugen stimmen ab. Ihr bleibt überrascht.',
        },
        {
            key: 'wayHome',
            time: '00:10',
            icon: 'clock',
            name: 'Heimweg',
            line: 'Eine halbe Stunde vor dem letzten Schiff eine Mail an alle, die noch tanzen.',
        },
    ],
    extras: {
        title: 'Und obendrauf.',
        lede: 'Einzeln zu jedem Paket. Mit Couture alles dabei.',
        add: 'Einzeln buchbar',
        included: 'In Couture dabei',
        perYear: 'pro Jahr',
        fromAtelier: 'Ab Atelier',
        items: [
            {
                icon: 'template',
                addon: 'newspaper',
                name: 'Hochzeitszeitung',
                line: 'Eure Freunde schreiben Geschichten und schicken Fotos. Die Trauzeugen machen daraus eure Zeitung für den Abend.',
                couture: true,
            },
            {
                icon: 'calendar',
                addon: 'chase_service',
                name: 'Wir fassen nach',
                line: 'Wer zwei Wochen vor der Frist noch nicht geantwortet hat, hört von uns. Ihr müsst niemandem nachlaufen.',
                couture: true,
            },
            {
                icon: 'link',
                addon: 'custom_domain',
                name: 'Eigene Domain',
                line: 'Eure Seite unter eurem eigenen Namen, etwa anna-und-luca.ch. Wir richten alles für euch ein.',
                couture: true,
            },
            {
                icon: 'forever',
                addon: 'keep_memories',
                name: 'Erinnerungen behalten',
                line: 'Fotos und Zeitung bleiben ein Jahr länger. Die Daten eurer Gäste löschen wir trotzdem pünktlich. Ab Atelier.',
                couture: false,
            },
        ],
    },
} as const;

/**
 * «Was es kostet» (PricingSection): the smallest package first and large,
 * the two bigger ones below, the extras last. Plan contents come from
 * content/publish.ts, the same lines couples read at checkout; prices from
 * WelcomeController.
 */
export const pricing = {
    title: 'Was es kostet.',
    lede: 'Einmal bezahlt. Kein Abo, kein Preis pro Gast, keine Werbung.',
    lead: {
        title: 'Eure Hochzeitswebsite',
        line: 'Signature, das kleinste Paket: eure Seite mit einem persönlichen Link für jeden Haushalt.',
        free: 'Gestalten und Vorschau sind gratis. Ihr bezahlt erst, wenn die Links an eure Gäste gehen.',
        shot: 'Der Entwurf Garten auf dem Handy',
    },
    once: 'einmalig',
    from: 'ab',
    moreTitle: 'Wenn ihr mehr wollt',
    /* What each bigger package adds, as the brand's own icons. */
    icons: {
        atelier: ['dinner', 'music', 'megaphone', 'clock'],
        couture: ['template', 'calendar', 'link', 'forever'],
    },
    lines: {
        atelier:
            'Die Website und der Abend, der von selbst läuft, mit den Fotos eurer Gäste.',
        couture: 'Alles, und wir gestalten und begleiten es mit euch.',
    },
    start: 'Kostenlos starten',
    ask: 'Anfragen',
    askHref: 'mailto:info@hereby.ch?subject=Couture',
    extrasTitle: 'Einzeln dazu',
    extras: [
        { addon: 'newspaper', name: 'Hochzeitszeitung', perYear: false },
        { addon: 'chase_service', name: 'Wir fassen nach', perYear: false },
        { addon: 'custom_domain', name: 'Eigene Domain', perYear: false },
        {
            addon: 'keep_memories',
            name: 'Erinnerungen behalten',
            perYear: true,
        },
        { addon: 'bespoke', name: 'Massanfertigung', perYear: false },
    ],
    perYear: 'pro Jahr',
} as const;

export const struck = {
    titleLead: 'Hiermit',
    titleAccent: 'gestrichen.',
    lede: 'Was andere Hochzeitswebsites mitbringen und eure Gäste nicht brauchen.',
    items: [
        {
            word: 'Passwörter',
            icon: 'key',
            instead: 'Ein privater Link pro Haushalt. Kein Konto, keine App.',
        },
        {
            word: 'Vorlagen',
            icon: 'template',
            instead:
                'Fünf fertig gestaltete Entwürfe. Oder eine Seite, die wir für euch bauen.',
        },
        {
            word: 'Eine Sprache',
            icon: 'language',
            instead: 'Deutsch, Français, Italiano, English. Pro Haushalt.',
        },
        {
            word: 'Ein Termin',
            icon: 'calendar',
            instead:
                'Ziviltrauung, Apéro, Fest, Brunch. Jeder mit seiner Gästeliste.',
        },
        {
            word: 'Werbung',
            icon: 'megaphone',
            instead: 'Eure Gäste sehen nie Angebote Dritter.',
        },
        {
            word: 'Daten für immer',
            icon: 'forever',
            instead: 'Verschlüsselt, und zwölf Monate nach dem Fest gelöscht.',
        },
    ],
} as const;

export const faq = {
    title: 'Häufige Fragen zur Hochzeitswebsite',
} as const;

export const closing = {
    title: 'Euer Sommer 2027 beginnt mit einer Einladung.',
    lede: 'Baut eure Hochzeitswebsite kostenlos. Bezahlt erst, wenn die Einladungen an eure Gäste gehen.',
    cta: 'Kostenlos starten',
    photo: photo.sparklers,
} as const;

export const footer = {
    privacy: 'Datenschutz',
    imprint: 'Impressum',
    email: 'info@hereby.ch',
} as const;

/** The topic pages (lang/de_CH/pages.php): shared copy and the footer list. */
export const topics = {
    cta: 'Kostenlos starten',
    examples: 'Beispiele ansehen',
    faq: 'Häufige Fragen',
    related: 'Weiterlesen',
    demo: 'Beispiel mit Demo-Daten',
    closing:
        'Probiert es aus: Einrichten ist kostenlos, bezahlt wird erst beim Verschicken.',
    links: [
        {
            slug: 'hochzeitswebsite-erstellen',
            label: 'Hochzeitswebsite erstellen',
        },
        {
            slug: 'hochzeitseinladung-online',
            label: 'Hochzeitseinladung online',
        },
        { slug: 'hochzeit-rsvp-online', label: 'Online-RSVP' },
        { slug: 'gaesteliste-hochzeit', label: 'Gästeliste Hochzeit' },
        {
            slug: 'mehrsprachige-hochzeitswebsite',
            label: 'Mehrsprachige Hochzeit',
        },
        {
            slug: 'hochzeitshomepage-passwort',
            label: 'Hochzeitshomepage ohne Passwort',
        },
        { slug: 'fuer-locations', label: 'Für Hochzeitslocations' },
        { slug: 'preise', label: 'Preise' },
    ],
} as const;
