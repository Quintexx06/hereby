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
    statement: 'Die Hochzeitswebsite, die jeden Gast persönlich einlädt.',
    lede: 'Ein eigener Link für jeden Haushalt, in seiner Sprache. Eure Gäste antworten in unter einer Minute, und wir richten alles in 48 Stunden für euch ein.',
    primaryCta: 'Demo anfragen',
    secondaryCta: 'So funktioniert’s',
    skip: 'Vorhang öffnen',
    photo: photo.heroVeil,
} as const;

export const intro = {
    title: 'Eure Gäste bekommen keine Website. Sie bekommen ihre Einladung.',
    body: 'Eine Hochzeitshomepage für alle zeigt jedem alles: das Abendessen, zu dem nicht alle eingeladen sind, das Formular mit Fragen, die nicht passen, das Passwort, das niemand findet. Hereby dreht das um. Jeder Haushalt öffnet seine eigene Einladung, mit seinen Namen, seinen Anlässen und seiner Sprache.',
} as const;

export const guests = {
    title: 'Ein Link pro Haushalt. In seiner Sprache.',
    lede: 'Die Familie aus Lausanne liest Französisch, die Freunde aus London Englisch. Alle sehen nur, wozu sie eingeladen sind.',
    points: [
        {
            icon: 'link',
            title: 'Kein Passwort, keine App',
            body: 'Ein privater Link genügt. Suchmaschinen sehen ihn nie.',
        },
        {
            icon: 'events',
            title: 'Nur, was gilt',
            body: 'Ziviltrauung, Apéro, Abendessen, Fest: Jeder Haushalt sieht seine Anlässe.',
        },
        {
            icon: 'clock',
            title: 'Antwort in unter 60 Sekunden',
            body: 'Namen sind ausgefüllt. Gefragt wird nur, was für diese Gäste zählt.',
        },
    ],
    previewLabel:
        'Beispiel: dieselbe Einladung für drei Haushalte, auf Deutsch, Französisch und Englisch',
    photo: photo.veilKiss,
} as const;

export const steps = {
    title: 'Von der ersten Idee bis zur letzten Zusage.',
    items: [
        {
            figure: '20',
            unit: 'Min.',
            title: 'Ein Gespräch',
            body: 'Ihr erzählt uns von eurem Tag: Orte, Anlässe, Gäste, Sprachen. Mehr braucht es nicht.',
        },
        {
            figure: '48',
            unit: 'Std.',
            title: 'Eure Website steht',
            body: 'Wir gestalten und richten alles ein. Ihr prüft, wir passen an. Ohne Baukasten, ohne Vorlagen-Look.',
        },
        {
            figure: '60',
            unit: 'Sek.',
            title: 'Eure Gäste antworten',
            body: 'Jeder Haushalt öffnet seinen Link und sagt zu, auf dem Handy, in unter einer Minute.',
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
    lede: 'Wählt eine Stimmung, und alles folgt: Farben, Kontraste, die Einladung eurer Gäste. Jede Website wird von Hand auf euren Tag abgestimmt.',
    hint: 'Stimmung wählen',
    previous: 'Vorherige Stimmung',
    next: 'Nächste Stimmung',
    themes: [
        { slug: 'ivory', name: 'Ivory', mood: 'Porzellan, Tinte, Kerzenlicht' },
        {
            slug: 'alpine',
            name: 'Alpine',
            mood: 'Gletscherweiss, Bergsee, Schiefer',
        },
        {
            slug: 'riviera',
            name: 'Riviera',
            mood: 'Kalkstein, Nachtmeer, Indigo',
        },
        {
            slug: 'lavanda',
            name: 'Lavanda',
            mood: 'Flieder, Aubergine, Sommerwiese',
        },
        { slug: 'rose', name: 'Rosé', mood: 'Puder, Beere, Pfingstrose' },
    ],
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
            instead: 'Von Hand gestaltet, in 48 Stunden eingerichtet.',
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
    lede: 'Erzählt uns in zwanzig Minuten von eurem Tag. Zwei Tage später steht eure Hochzeitswebsite.',
    cta: 'Demo anfragen',
    photo: photo.sparklers,
} as const;

export const footer = {
    privacy: 'Datenschutz',
    imprint: 'Impressum',
    email: 'info@hereby.ch',
} as const;
