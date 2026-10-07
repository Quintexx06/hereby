/**
 * Privacy policy and imprint, Swiss German. They describe what the code does
 * today (no tracking, self-hosted fonts and images, encrypted dietary notes,
 * deletion 12 months after the wedding). Everything in [square brackets] is a
 * placeholder the company must fill in; a lawyer reviews both before launch
 * (roadmap 1.14).
 */
export type LegalSection = { heading: string; paragraphs: string[] };

export type LegalPage = {
    title: string;
    updated: string;
    draftNote: string;
    sections: LegalSection[];
};

const draftNote =
    'Entwurf: Angaben in [eckigen Klammern] werden vor dem Start ergänzt, und der Text wird von einer Fachperson geprüft.';

export const privacy: LegalPage = {
    title: 'Datenschutzerklärung',
    updated: 'Stand: [Datum]',
    draftNote,
    sections: [
        {
            heading: 'Wer verantwortlich ist',
            paragraphs: [
                'Verantwortlich für die Bearbeitung eurer Personendaten ist [Firmenname], [Strasse Nr.], [PLZ Ort], Schweiz. Fragen zum Datenschutz beantworten wir unter [datenschutz@domain.ch].',
                'Wir richten uns nach dem Schweizer Datenschutzgesetz (DSG) und, soweit anwendbar, nach der Datenschutz-Grundverordnung der EU (DSGVO).',
            ],
        },
        {
            heading: 'Welche Daten wir bearbeiten',
            paragraphs: [
                'Besuch der Website: Unser Server protokolliert technisch notwendige Angaben (IP-Adresse, Zeitpunkt, aufgerufene Seite, Browser), um die Website sicher zu betreiben und Missbrauch zu erkennen.',
                'Fragen über das Formular: Eure E-Mail-Adresse und eure Frage. Wir nutzen sie nur, um euch zu antworten.',
                'Konto eines Paares: Name, E-Mail-Adresse und Passwort (nur als sicherer Hash gespeichert), optional Passkeys und Zwei-Faktor-Angaben.',
                'Gästedaten auf der persönlichen Einladung: Namen, Haushalt, Sprache, Zu- und Absagen sowie Angaben zu Menü, Allergien und Unverträglichkeiten. Diese Daten bearbeiten wir im Auftrag des Brautpaares; Allergien und Unverträglichkeiten speichern wir verschlüsselt.',
            ],
        },
        {
            heading: 'Cookies und Tracking',
            paragraphs: [
                'Wir setzen nur technisch notwendige Cookies: für die Sitzung, den Schutz vor gefälschten Anfragen (CSRF) und die gewählte Darstellung (hell oder dunkel).',
                'Wir verwenden keine Analyse-, Werbe- oder Tracking-Cookies und keine Social-Media-Plugins. Schriften und Bilder liefern wir von unseren eigenen Servern aus; beim Besuch werden keine Daten an Google Fonts oder ähnliche Dienste übermittelt.',
            ],
        },
        {
            heading: 'Weitergabe an Dritte',
            paragraphs: [
                'Wir geben Personendaten nur an Dienstleister weiter, die wir für den Betrieb brauchen: Hosting ([Anbieter, Standort]) und E-Mail-Versand ([Anbieter, Standort]). Sie bearbeiten die Daten nur nach unseren Weisungen.',
                'Wir verkaufen keine Daten. Eure Gäste sehen nie Werbung oder Angebote Dritter.',
            ],
        },
        {
            heading: 'Wie lange wir Daten aufbewahren',
            paragraphs: [
                'Gästedaten löschen wir automatisch zwölf Monate nach dem Hochzeitsdatum.',
                'Fragen über das Formular bewahren wir bis zur Beantwortung und danach höchstens [12 Monate] auf. Server-Protokolle löschen wir nach [30 Tagen].',
                'Konten bestehen, bis sie gelöscht werden. Ihr könnt euer Konto jederzeit in den Einstellungen löschen.',
            ],
        },
        {
            heading: 'Sicherheit',
            paragraphs: [
                'Die Verbindung ist verschlüsselt (HTTPS). Persönliche Einladungslinks sind nicht erratbar und werden von Suchmaschinen nicht erfasst. Zugriff auf Gästedaten haben nur das Brautpaar und, für den Betrieb, wir.',
            ],
        },
        {
            heading: 'Eure Rechte',
            paragraphs: [
                'Ihr könnt Auskunft über eure Daten verlangen, sie berichtigen oder löschen lassen, ihre Herausgabe verlangen und der Bearbeitung widersprechen. Schreibt uns dazu an [datenschutz@domain.ch].',
                'Gäste wenden sich für ihre Einladungsdaten am einfachsten an das Brautpaar; wir unterstützen es dabei.',
                'Ihr habt zudem das Recht, euch beim Eidgenössischen Datenschutz- und Öffentlichkeitsbeauftragten (EDÖB) zu beschweren.',
            ],
        },
        {
            heading: 'Änderungen',
            paragraphs: [
                'Wir passen diese Erklärung an, wenn sich unsere Bearbeitung ändert. Es gilt die jeweils hier veröffentlichte Fassung.',
            ],
        },
    ],
};

export const imprint: LegalPage = {
    title: 'Impressum',
    updated: 'Stand: [Datum]',
    draftNote,
    sections: [
        {
            heading: 'Betreiberin',
            paragraphs: [
                '[Firmenname]',
                '[Strasse Nr.], [PLZ Ort], Schweiz',
                'E-Mail: [kontakt@domain.ch]',
            ],
        },
        {
            heading: 'Vertretungsberechtigte Person',
            paragraphs: ['[Vorname Name]'],
        },
        {
            heading: 'Handelsregister und UID',
            paragraphs: [
                'Eingetragen im Handelsregister des Kantons [Kanton]',
                'UID: [CHE-xxx.xxx.xxx]',
                'Mehrwertsteuernummer: [CHE-xxx.xxx.xxx MWST]',
            ],
        },
        {
            heading: 'Haftung',
            paragraphs: [
                'Wir prüfen die Inhalte dieser Website sorgfältig, übernehmen aber keine Gewähr für ihre Richtigkeit, Vollständigkeit und Aktualität. Für Inhalte verlinkter Websites sind ausschliesslich deren Betreiber verantwortlich.',
            ],
        },
        {
            heading: 'Urheberrechte',
            paragraphs: [
                'Texte, Gestaltung und Grafiken dieser Website gehören [Firmenname]. Die Fotos stammen von Unsplash und werden gemäss der Unsplash-Lizenz verwendet. Jede weitere Verwendung braucht unsere vorherige Zustimmung.',
            ],
        },
    ],
};
