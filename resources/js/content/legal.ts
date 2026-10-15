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

const updated = 'Stand: 14. Oktober 2026';
const operator = 'Hereby, Martin Brunner';
const contact = 'info@hereby.ch';

export const privacy: LegalPage = {
    title: 'Datenschutzerklärung',
    updated,
    draftNote,
    sections: [
        {
            heading: 'Wer verantwortlich ist',
            paragraphs: [
                `Verantwortlich für die Bearbeitung eurer Personendaten ist ${operator}, [Strasse Nr.], [PLZ] Zürich, Schweiz. Fragen zum Datenschutz beantworten wir unter ${contact}.`,
                'Wir richten uns nach dem Schweizer Datenschutzgesetz (DSG) und, soweit anwendbar, nach der Datenschutz-Grundverordnung der EU (DSGVO).',
            ],
        },
        {
            heading: 'Welche Daten wir bearbeiten',
            paragraphs: [
                'Besuch der Website: Unser Server protokolliert technisch notwendige Angaben (IP-Adresse, Zeitpunkt, aufgerufene Seite, Browser), um die Website sicher zu betreiben und Missbrauch zu erkennen.',
                'Konto eines Paares: Name, E-Mail-Adresse und Passwort (nur als sicherer Hash gespeichert), optional Passkeys und Zwei-Faktor-Angaben, dazu alles, was ihr für eure Hochzeitsseite eintragt, auch eure Fotos.',
                'Gästedaten: Namen, Haushalt, Sprache, E-Mail-Adresse und, falls das Paar sie einträgt, Telefonnummer; Zu- und Absagen sowie Angaben zu Menü, Allergien und Unverträglichkeiten. Allergien und Unverträglichkeiten speichern wir verschlüsselt. Diese Daten bearbeiten wir im Auftrag des Brautpaares.',
                'Je nach Paket des Paares zusätzlich: der Tisch eines Gastes mit einer Zeile, die das Paar über ihn schreibt (seine Tischnachbarn sehen den Vornamen und diese Zeile, nie Nachnamen oder Allergien); Reden, Spiele oder Lieder, die Gäste für den Abend anmelden; Gruppenfotos, in die das Paar Gäste einteilt; Fotos, die Gäste hochladen; Geschichten und Fotos für die Hochzeitszeitung.',
                'Partner: Locations, Fotografen und Planer haben ein eigenes Konto (Name, Kontaktangaben, bei Locations Adresse und Beschreibung). Fragt jemand über die öffentliche Seite einer Location eine Besichtigung an, speichern wir Name, E-Mail-Adresse, Wunschtermin und Nachricht für diese Location.',
                'Zahlungen: Paare bezahlen über Stripe. Kartendaten und TWINT-Angaben gehen direkt an Stripe; wir erhalten nur die Bestätigung der Zahlung.',
            ],
        },
        {
            heading: 'Wozu wir E-Mails schicken',
            paragraphs: [
                'Gäste mit E-Mail-Adresse bekommen eine Bestätigung ihrer Antwort, Erinnerungen vor der Antwortfrist (abbestellbar mit einem Klick) und, je nach Paket, am Tag selbst eine Mail zum Gruppenfoto oder vor der letzten Verbindung nach Hause. Paare bekommen Mails zu ihrem Konto.',
                'Hat ein Paar «Wir fassen nach» gebucht, kontaktieren wir Gäste ohne Antwort zwei Wochen vor der Frist per E-Mail oder Telefon, nur um an die Antwort zu erinnern.',
            ],
        },
        {
            heading: 'Cookies und Tracking',
            paragraphs: [
                'Wir setzen nur technisch notwendige Cookies: für die Sitzung, den Schutz vor gefälschten Anfragen (CSRF) und die gewählte Darstellung.',
                'Wir verwenden keine Analyse-, Werbe- oder Tracking-Cookies und keine Social-Media-Plugins. Schriften und Bilder liefern wir von unseren eigenen Servern aus; beim Besuch werden keine Daten an Google Fonts oder ähnliche Dienste übermittelt.',
            ],
        },
        {
            heading: 'Weitergabe an Dritte',
            paragraphs: [
                'Wir geben Personendaten nur an Dienstleister weiter, die wir für den Betrieb brauchen: Hosting ([Anbieter, Standort]), E-Mail-Versand ([Anbieter, Standort]) und Zahlungen (Stripe Payments Europe, Irland). Sie bearbeiten die Daten nur nach unseren Weisungen.',
                'Verbindet ein Paar seine Location, seinen Fotografen oder seinen Planer mit Hereby, sieht dieser Partner nur, was das Paar damit freigibt: eine Location oder ein Planer die Zahlen, Menüs und Allergien pro Tisch für die Schlussliste, ein Fotograf nur die Möglichkeit, Fotos in die Erinnerungen des Paares hochzuladen. Das Paar kann die Verbindung jederzeit lösen.',
                'Wir verkaufen keine Daten. Eure Gäste sehen nie Werbung oder Angebote Dritter; höchstens eine Zeile nennt den Ort des Festes.',
            ],
        },
        {
            heading: 'Wie lange wir Daten aufbewahren',
            paragraphs: [
                'Gästedaten löschen wir automatisch zwölf Monate nach dem Hochzeitsdatum: Haushalte, Namen, Antworten, Allergien, Tische, Anmeldungen und Gruppenfotos.',
                'Bucht ein Paar «Erinnerungen behalten», bleiben die Fotos der Gäste und die Hochzeitszeitung (mit den Namen, mit denen Gäste ihre Beiträge unterschrieben haben) jeweils ein Jahr länger; die übrigen Gästedaten löschen wir trotzdem nach zwölf Monaten.',
                'Anfragen für eine Besichtigung löschen wir zwölf Monate nach Eingang.',
                'Server-Protokolle löschen wir nach [30 Tagen]. Konten bestehen, bis sie gelöscht werden; ihr könnt euer Konto jederzeit in den Einstellungen löschen, und mit ihm alle Daten eurer Hochzeit.',
            ],
        },
        {
            heading: 'Sicherheit',
            paragraphs: [
                'Die Verbindung ist verschlüsselt (HTTPS). Persönliche Einladungslinks, der Foto-Link, der Link für die Hochzeitszeitung und der Link für die Trauzeugen sind nicht erratbar und werden von Suchmaschinen nicht erfasst. Fotos sind privat und nur für das Paar sichtbar. Zugriff auf Gästedaten haben nur das Brautpaar und, für den Betrieb, wir.',
            ],
        },
        {
            heading: 'Eure Rechte',
            paragraphs: [
                `Ihr könnt Auskunft über eure Daten verlangen, sie berichtigen oder löschen lassen, ihre Herausgabe verlangen und der Bearbeitung widersprechen. Schreibt uns dazu an ${contact}.`,
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
    updated,
    draftNote,
    sections: [
        {
            heading: 'Betreiber',
            paragraphs: [
                operator,
                '[Strasse Nr.], [PLZ] Zürich, Schweiz',
                `E-Mail: ${contact}`,
            ],
        },
        {
            heading: 'Verantwortliche Person',
            paragraphs: ['Martin Brunner'],
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
                'Texte, Gestaltung und Grafiken dieser Website gehören Martin Brunner. Die Fotos stammen von Unsplash und werden gemäss der Unsplash-Lizenz verwendet. Jede weitere Verwendung braucht unsere vorherige Zustimmung.',
            ],
        },
    ],
};
