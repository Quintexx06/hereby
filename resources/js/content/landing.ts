/**
 * Landing page copy, Swiss German (no "ß"), addressed to the couple as "ihr".
 * Keyword research, message hierarchy and UX rationale:
 * docs/marketing/landing-page.md. SEO strings and the FAQ live in
 * lang/de_CH/landing.php because the server renders them too.
 */
import { landingPhotos as photo } from '@/content/landing-photos';

export const nav = [
    { label: 'Für Gäste', href: '/#gaeste' },
    { label: 'Ablauf', href: '/#ablauf' },
    { label: 'Der Tag', href: '/#tag' },
    { label: 'Fragen', href: '/#fragen' },
] as const;

export const hero = {
    titleLead: 'Vorhang auf',
    titleTail: 'für euer',
    titleYes: 'Ja.',
    statement:
        'Die Hochzeitswebsite aus der Schweiz, die jeden Gast persönlich einlädt.',
    lede: 'Jeder Haushalt bekommt seinen eigenen Link: mit seinen Namen, seinen Anlässen, in seiner Sprache. Eure Gäste sagen in unter einer Minute zu, und ihr seht jede Antwort an einem Ort.',
    primaryCta: 'Kostenlos starten',
    secondaryCta: 'So funktioniert’s',
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
    lede: 'Drei Vorlagen, jede mit eigenem Auftritt: eure Fotos, euer Programm, eure Anreise. Scrollt durch die Seiten, setzt eure Namen ein und wählt eine Akzentfarbe.',
    templatesLabel: 'Vorlagen',
    previous: 'Vorherige Vorlage',
    next: 'Nächste Vorlage',
    open: 'Ganze Seite ansehen',
    close: 'Schliessen',
    start: 'Mit diesem Design starten',
    templates: [
        {
            slug: 'atelier',
            name: 'Atelier',
            mood: 'Grosse Schrift über eurem Foto, ruhig und editorial',
        },
        {
            slug: 'garten',
            name: 'Garten',
            mood: 'Ein Torbogen, Zweige und viel Luft',
        },
        {
            slug: 'panorama',
            name: 'Panorama',
            mood: 'Eure Namen als Laufband, Fotos in voller Breite',
        },
    ],
    pickerLabel: 'Akzentfarbe der Beispielseite',
    example: 'Beispielseite mit Demo-Daten',
    tryLead: 'Probiert es mit euren Namen:',
    tryJoin: 'am',
    firstLabel: 'Erster Vorname',
    secondLabel: 'Zweiter Vorname',
    dateLabel: 'Hochzeitsdatum',
    accents: [
        { slug: 'ivory', name: 'Ivory', mood: 'Porzellan, Tinte, Kerzenlicht' },
        { slug: 'rose', name: 'Rosé', mood: 'Puder, Himbeere, Pfingstrose' },
        {
            slug: 'alpine',
            name: 'Alpine',
            mood: 'Gletscherweiss, Seeblau, Arve',
        },
    ],
    site: {
        couple: { first: 'Anna', second: 'Luca', date: '2027-06-19' },
        place: 'Vitznau',
        welcomeKicker: 'Wir heiraten',
        nav: ['Programm', 'Anreise', 'Fotos'],
        action: 'Jetzt antworten',
        welcome:
            'Wir sagen Ja am Vierwaldstättersee. Kommt vorbei, bringt gute Schuhe mit und bleibt bis zum letzten Schiff.',
        /* The same welcome, split so a template can set «Ja» as the accent. */
        welcomeLead: 'Wir sagen',
        welcomeAccent: 'Ja',
        welcomeTail: 'am Vierwaldstättersee.',
        welcomeRest:
            'Kommt vorbei, bringt gute Schuhe mit und bleibt bis zum letzten Schiff.',
        replyTitle: 'Kommt ihr?',
        replyBy: 'Bitte antwortet bis 1. Mai 2027',
        footer: {
            lead: 'Fragen? Meldet euch bei',
            name: 'Lea, Trauzeugin',
            email: 'lea@anna-und-luca.ch',
            credit: 'Hochzeitswebsite von Hereby',
            privacy: 'Datenschutz',
        },
        programmeTitle: 'Programm',
        events: [
            { time: '15:30', name: 'Apéro', place: 'Seeterrasse' },
            { time: '18:30', name: 'Abendessen', place: 'Grand Salon' },
            { time: '22:00', name: 'Fest', place: 'Bootshaus' },
        ],
        travelTitle: 'Anreise',
        travel: 'Ab Luzern mit dem Schiff in 55 Minuten. Das letzte fährt um 00:40.',
        galleryTitle: 'Fotos von euch',
        replyNote: 'Heidi, Peter und Lina haben zugesagt',
        photos: {
            hero: photo.sparklers,
            arch: photo.veilKiss,
            strip: [photo.sparklers, photo.tableCandles, photo.bouquet],
            venue: photo.lakeJetty,
            gallery: [photo.veilKiss, photo.tableCandles, photo.bouquet],
        },
    },
} as const;

/**
 * The paid side, without prices: only what ships today (rule 5). Two
 * products, told apart: the Atelier evening as moments of the night, and
 * the extras as tickets to add one by one.
 */
export const premium = {
    titleLead: 'Und dann',
    titleAccent: 'der Abend.',
    lede: 'Die Einladung ist gratis gebaut. Mit Atelier geht eure Seite weiter: Sie führt eure Gäste durch den Tag, gibt den Trauzeugen das Programm in die Hand und bringt alle sicher heim.',
    package: {
        badge: 'Paket Atelier',
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
        items: [
            {
                icon: 'template',
                name: 'Hochzeitszeitung',
                line: 'Eure Freunde schreiben Geschichten und schicken Fotos. Die Trauzeugen machen daraus eure Zeitung für den Abend.',
                couture: true,
            },
            {
                icon: 'calendar',
                name: 'Wir fassen nach',
                line: 'Wer zwei Wochen vor der Frist noch nicht geantwortet hat, hört von uns. Ihr müsst niemandem nachlaufen.',
                couture: true,
            },
            {
                icon: 'link',
                name: 'Eigene Domain',
                line: 'Eure Seite unter eurem eigenen Namen, etwa anna-und-luca.ch. Wir richten alles für euch ein.',
                couture: true,
            },
            {
                icon: 'forever',
                name: 'Erinnerungen behalten',
                line: 'Fotos und Zeitung bleiben ein Jahr länger. Die Daten eurer Gäste löschen wir trotzdem pünktlich. Ab Atelier.',
                couture: false,
            },
        ],
    },
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
                'Drei gestaltete Looks mit eurer Farbe. Oder eine Seite, die wir für euch bauen.',
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
