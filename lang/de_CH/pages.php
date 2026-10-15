<?php

use App\Support\Chf;

/*
 * SEO subpages (docs/marketing/landing-page.md, section 8). One topic and one
 * search intent per page, Swiss German (no "ß"), addressed as "ihr". Rendered
 * by pages/Topic.vue; meta tags and FAQPage data come from the same array on
 * the server. Prices are read from config, so the pages never disagree with
 * checkout.
 */
$signature = Chf::price('plans.signature');
$atelier = Chf::price('plans.atelier');
$couture = Chf::price('plans.couture');

return [
    'hochzeitswebsite-erstellen' => [
        'label' => 'Hochzeitswebsite erstellen',
        'title' => 'Hochzeitswebsite erstellen – in 10 Minuten, ohne Baukasten',
        'description' => 'Eure Hochzeitswebsite in der Schweiz erstellen: sieben kurze Fragen, drei gestaltete Looks, ein persönlicher Link pro Haushalt. Kostenlos einrichten.',
        'h1' => 'Eure Hochzeitswebsite, in zehn Minuten erstellt.',
        'lede' => 'Kein Baukasten, keine leeren Seiten. Ihr beantwortet sieben kurze Fragen, und eure Hochzeitswebsite steht: mit Programm, Anreise und Antwortformular. Einrichten ist kostenlos, bezahlt wird erst, wenn die Einladungen verschickt werden.',
        'sections' => [
            [
                'title' => 'Sieben Fragen statt hundert Einstellungen',
                'body' => [
                    'Andere Anbieter geben euch einen Editor und viel Platz. Wir fragen nur, was eine Hochzeit in der Schweiz wirklich braucht, und bauen daraus eure Seite.',
                ],
                'points' => [
                    ['title' => 'Paar und Datum', 'body' => 'Eure Namen, der grosse Tag und bis wann eure Gäste antworten sollen.'],
                    ['title' => 'Ort und Ablauf', 'body' => 'Ziviltrauung, Apéro, Abendessen, Fest, Brunch: jeder Anlass mit Zeit und Ort.'],
                    ['title' => 'Gäste und Sprachen', 'body' => 'Deutsch, Französisch, Italienisch oder Englisch, pro Haushalt gewählt.'],
                    ['title' => 'Look', 'body' => 'Atelier, Garten oder Panorama, mit euren Fotos und eurer Akzentfarbe.'],
                ],
            ],
            [
                'title' => 'Gestaltet, nicht zusammengeklickt',
                'body' => [
                    'Jede Vorlage ist fertig gestaltet: Schrift, Abstände und Bewegung stimmen schon. Ihr wählt einen Look und eine Farbe, eure Fotos machen den Rest. So sieht eure Seite auch auf dem kleinsten Handy aus wie geplant.',
                    'Ihr habt eine ganz eigene Vorstellung? Mit der Massanfertigung schickt ihr uns eure Wünsche, Farben und Bilder, und wir bauen eure Seite für euch.',
                ],
            ],
            [
                'title' => 'Alles, was eure Gäste wissen wollen',
                'body' => [
                    'Programm, Anreise mit Zug, Schiff oder Auto, Übernachten, Dresscode, Geschenke und eure Fragen und Antworten: Die Blöcke sind vorbereitet, ihr schreibt nur noch eure Sätze hinein. Den Termin tragen eure Gäste mit einem Tipp in ihren Kalender ein.',
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Wie lange dauert es, eine Hochzeitswebsite zu erstellen?', 'answer' => 'Die Grundlagen stehen in rund zehn Minuten. Texte, Fotos und das Antwortformular verfeinert ihr danach, wann ihr wollt.'],
            ['question' => 'Brauchen wir Kenntnisse in Webdesign?', 'answer' => 'Nein. Ihr beantwortet Fragen und wählt einen Look. Alles Gestalterische ist schon erledigt.'],
            ['question' => 'Was kostet das Erstellen?', 'answer' => "Nichts. Ihr könnt alles einrichten und in der Vorschau ansehen. Bezahlt wird einmalig, wenn ihr die persönlichen Links verschickt: ab {$signature}."],
        ],
        'related' => ['preise', 'hochzeitseinladung-online', 'mehrsprachige-hochzeitswebsite'],
    ],

    'hochzeitseinladung-online' => [
        'label' => 'Hochzeitseinladung online',
        'title' => 'Hochzeitseinladung online verschicken – pro Haushalt',
        'description' => 'Die Hochzeitseinladung online verschicken, per WhatsApp oder Mail: jeder Haushalt bekommt seinen eigenen Link, mit seinen Namen und Anlässen.',
        'h1' => 'Die Hochzeitseinladung, die jeden Haushalt beim Namen nennt.',
        'lede' => 'Eine Online-Einladung muss sich nicht wie ein Rundschreiben anfühlen. Bei Hereby öffnet jeder Haushalt seine eigene Einladung: mit seinen Namen, nur seinen Anlässen und in seiner Sprache.',
        'sections' => [
            [
                'title' => 'Ein Link pro Haushalt',
                'body' => [
                    'Ihr legt eure Gäste als Haushalte an, zum Beispiel «Familie Meier: Heidi, Peter, Lina». Jeder Haushalt bekommt einen privaten Link. Ihr teilt ihn per WhatsApp, SMS oder Mail, so wie ihr sonst auch schreibt.',
                    'Wer den Link öffnet, wird mit Namen begrüsst und sieht genau die Anlässe, zu denen er eingeladen ist. Die Familie sieht die Ziviltrauung, die Freunde den Apéro und das Fest.',
                ],
            ],
            [
                'title' => 'Online und auf Papier',
                'body' => [
                    'Viele Paare schicken eine gedruckte Karte und dazu den persönlichen Link. Die Karte ist das Geschenk, der Link erledigt die Organisation: Zusagen, Menüwahl, Allergien und Anreise.',
                ],
            ],
            [
                'title' => 'Was eure Gäste nie sehen',
                'points' => [
                    ['title' => 'Kein Passwort', 'body' => 'Der Link selbst ist der Schlüssel. Niemand muss sich etwas merken.'],
                    ['title' => 'Keine App, kein Konto', 'body' => 'Die Einladung öffnet im Browser, auf jedem Handy.'],
                    ['title' => 'Keine Werbung', 'body' => 'Eure Gäste sehen nie Angebote Dritter.'],
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Kann man eine Hochzeitseinladung per WhatsApp verschicken?', 'answer' => 'Ja. Ihr kopiert den persönlichen Link eines Haushalts und schickt ihn per WhatsApp, SMS oder Mail. Die Einladung öffnet direkt im Browser.'],
            ['question' => 'Sehen unsere Gäste, wer sonst eingeladen ist?', 'answer' => 'Nein. Jeder Haushalt sieht nur seine eigene Einladung und seine Anlässe.'],
            ['question' => 'Finden Suchmaschinen unsere Einladung?', 'answer' => 'Nein. Persönliche Einladungen sind für Suchmaschinen gesperrt und nur über den Link erreichbar.'],
        ],
        'related' => ['hochzeit-rsvp-online', 'hochzeitshomepage-passwort', 'gaesteliste-hochzeit'],
    ],

    'hochzeit-rsvp-online' => [
        'label' => 'Online-RSVP',
        'title' => 'Hochzeit RSVP online – Zu- und Absagen in 60 Sekunden',
        'description' => 'Online-RSVP für eure Hochzeit: Gäste sagen in unter einer Minute zu, wählen ihr Menü und nennen Allergien. Ihr seht jede Antwort sofort, mit Erinnerungen.',
        'h1' => 'Zu- und Absagen in unter einer Minute.',
        'lede' => 'Kein Formular mit zwanzig Feldern. Die Namen sind schon ausgefüllt, und gefragt wird nur, was für diesen Haushalt gilt. So antworten eure Gäste auf dem Handy, bevor sie es vergessen.',
        'sections' => [
            [
                'title' => 'Nur die Fragen, die zählen',
                'body' => [
                    'Ihr bestimmt im Antwortformular, was ihr wissen wollt: Menüwahl, Allergien und Unverträglichkeiten, Kinder, Shuttle oder Übernachtung. Jeder Haushalt beantwortet nur die Fragen zu seinen Anlässen.',
                ],
            ],
            [
                'title' => 'Ihr seht jede Antwort sofort',
                'body' => [
                    'Auf eurer Übersicht seht ihr, wer zugesagt hat, wer die Einladung geöffnet hat und wer noch nicht. Menüzahlen und Allergien pro Tisch gehen als Liste an Küche und Location, ohne Abtippen.',
                ],
            ],
            [
                'title' => 'Erinnerungen, ohne nachzulaufen',
                'body' => [
                    'Vor der Antwortfrist erinnern wir Haushalte, die noch nicht geantwortet haben, automatisch per Mail. Wer möchte, bucht «Wir fassen nach»: Dann melden wir uns persönlich bei allen, die zwei Wochen vor der Frist noch fehlen.',
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Was bedeutet RSVP bei einer Hochzeit?', 'answer' => 'RSVP steht für «Répondez s’il vous plaît», also «Antwortet bitte». Gemeint ist die Zu- oder Absage eurer Gäste bis zu einem bestimmten Datum.'],
            ['question' => 'Können Gäste ihre Antwort später ändern?', 'answer' => 'Ja. Über denselben Link passen sie ihre Antwort an, solange die Antworten offen sind. Ihr seht die Änderung sofort.'],
            ['question' => 'Werden Allergien sicher gespeichert?', 'answer' => 'Angaben zu Allergien und Unverträglichkeiten speichern wir verschlüsselt und nur für eure Küche. Zwölf Monate nach der Hochzeit werden die Daten eurer Gäste gelöscht.'],
        ],
        'related' => ['gaesteliste-hochzeit', 'hochzeitseinladung-online', 'fuer-locations'],
    ],

    'gaesteliste-hochzeit' => [
        'label' => 'Gästeliste Hochzeit',
        'title' => 'Gästeliste für die Hochzeit online verwalten',
        'description' => 'Eure Gästeliste für die Hochzeit an einem Ort: aus Excel oder Kontakten importieren, Zusagen, Menüs und Allergien im Blick, Zahlen für Küche und Location.',
        'h1' => 'Eure Gästeliste, die sich selbst nachführt.',
        'lede' => 'Schluss mit der Tabelle, die nach jeder Zusage veraltet ist. Bei Hereby trägt jede Antwort sich selbst ein, und ihr seht jederzeit, wer kommt, was gegessen wird und wer noch fehlt.',
        'sections' => [
            [
                'title' => 'In Minuten importiert',
                'body' => [
                    'Kopiert eure Liste aus Excel, Numbers oder Google Sheets, ladet eine Datei hoch oder nehmt die Kontakte vom Handy. Oder schreibt einfach einen Haushalt pro Zeile: «Familie Meier: Heidi, Peter, Lina (Kind)».',
                ],
            ],
            [
                'title' => 'Haushalte statt Einzelpersonen',
                'body' => [
                    'Hochzeitsgäste kommen als Paare und Familien. Deshalb denkt Hereby in Haushalten: ein Link, eine Antwort, alle Namen. Für jeden Haushalt legt ihr fest, zu welchen Anlässen er eingeladen ist und in welcher Sprache er liest.',
                ],
            ],
            [
                'title' => 'Zahlen für Küche und Location',
                'body' => [
                    'Menüs, Kinder und Allergien pro Tisch landen auf einer druckfertigen Liste. Verbindet ihr eure Location mit Hereby, sieht sie die aktuellen Zahlen selbst, ohne Namen und Kontaktdaten eurer Gäste.',
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Können wir unsere bestehende Gästeliste übernehmen?', 'answer' => 'Ja. Excel (.xlsx), CSV und Kontakte (.vcf) lassen sich hochladen, oder ihr kopiert die Zellen direkt hinein.'],
            ['question' => 'Wie viele Gäste sind möglich?', 'answer' => 'So viele, wie ihr einladet. Der Preis hängt nicht von der Zahl der Gäste ab.'],
            ['question' => 'Wer sieht unsere Gästeliste?', 'answer' => 'Nur ihr. Eure Location sieht Zahlen und Menüs, aber keine Namen und keine Kontaktdaten.'],
        ],
        'related' => ['hochzeit-rsvp-online', 'fuer-locations', 'hochzeitswebsite-erstellen'],
    ],

    'hochzeitshomepage-passwort' => [
        'label' => 'Hochzeitshomepage mit Passwort?',
        'title' => 'Hochzeitshomepage mit Passwort? Private Links sind besser',
        'description' => 'Eure Hochzeitshomepage schützen, ohne Passwort: Jeder Haushalt bekommt einen privaten Link. Sicher vor Suchmaschinen und Fremden, einfach für Grosseltern.',
        'h1' => 'Privat, ganz ohne Passwort.',
        'lede' => 'Ein gemeinsames Passwort schützt wenig: Es steht auf jeder Karte, wird weitergeschickt und von den Grosseltern vergessen. Hereby schützt eure Hochzeitshomepage anders, mit einem privaten Link für jeden Haushalt.',
        'sections' => [
            [
                'title' => 'Warum ein Passwort nicht reicht',
                'points' => [
                    ['title' => 'Alle haben dasselbe', 'body' => 'Wer das Passwort kennt, sieht alles, auch Anlässe, zu denen er nicht eingeladen ist.'],
                    ['title' => 'Es geht verloren', 'body' => 'Am Tag selbst sucht garantiert jemand die Karte mit dem Passwort.'],
                    ['title' => 'Es hält auf', 'body' => 'Jede Hürde vor dem Antworten kostet euch Zusagen.'],
                ],
            ],
            [
                'title' => 'So schützt Hereby eure Seite',
                'body' => [
                    'Jeder Haushalt bekommt einen langen, zufälligen Link, der sich nicht erraten lässt. Er öffnet nur diese eine Einladung, mit ihren Namen und Anlässen. Suchmaschinen sind ausgesperrt, und wer einen fremden Link weiterleitet, verrät höchstens eine Einladung, nicht die ganze Hochzeit.',
                    'Taucht ein Link an der falschen Stelle auf, erneuert ihr ihn mit einem Klick. Der alte funktioniert dann nicht mehr.',
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Ist eine Hochzeitswebsite ohne Passwort sicher?', 'answer' => 'Ja, wenn jeder Haushalt seinen eigenen, nicht erratbaren Link hat. Suchmaschinen indexieren die Einladungen nicht, und jeder Link zeigt nur eine Einladung.'],
            ['question' => 'Was passiert, wenn ein Link weitergeleitet wird?', 'answer' => 'Der Empfänger sieht nur diese eine Einladung. Ihr könnt den Link jederzeit erneuern.'],
            ['question' => 'Wo werden die Daten unserer Gäste gespeichert?', 'answer' => 'Bei Hereby, verschlüsselt, wo es um Gesundheitsangaben wie Allergien geht. Zwölf Monate nach der Hochzeit werden sie automatisch gelöscht.'],
        ],
        'related' => ['hochzeitseinladung-online', 'hochzeit-rsvp-online', 'preise'],
    ],

    'mehrsprachige-hochzeitswebsite' => [
        'label' => 'Mehrsprachige Hochzeit',
        'title' => 'Mehrsprachige Hochzeitswebsite in vier Sprachen',
        'description' => 'Eine Hochzeitswebsite in vier Sprachen: Deutsch, Französisch, Italienisch und Englisch, pro Haushalt gewählt. Für Hochzeiten über den Röstigraben hinaus.',
        'h1' => 'Jede Familie liest in ihrer Sprache.',
        'lede' => 'Eine Schweizer Hochzeit spricht selten nur eine Sprache. Bei Hereby legt ihr die Sprache pro Haushalt fest: Die Grosseltern im Tessin lesen Italienisch, die Familie in Lausanne Französisch, die Freunde aus London Englisch.',
        'sections' => [
            [
                'title' => 'Vier Sprachen, eine Hochzeit',
                'body' => [
                    'Deutsch mit Schweizer Schreibweise, Französisch, Italienisch und Englisch. Knöpfe, Formular, Daten und Uhrzeiten erscheinen in der Sprache des Haushalts, so wie man sie dort schreibt.',
                ],
            ],
            [
                'title' => 'Eure Texte, übersetzt',
                'body' => [
                    'Eure eigenen Texte, etwa zur Anreise oder zum Dresscode, schreibt ihr pro Sprache. Wer es nicht selbst machen möchte: Im Paket Couture übersetzen wir für euch.',
                ],
            ],
            [
                'title' => 'Antworten in einer Liste',
                'body' => [
                    'Egal, in welcher Sprache eure Gäste antworten: Ihr seht alle Zusagen, Menüs und Allergien in einer Übersicht auf Deutsch.',
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Kann jeder Gast seine Sprache selbst wählen?', 'answer' => 'Ihr legt die Sprache pro Haushalt fest. So öffnet jede Einladung gleich richtig, ohne dass jemand suchen muss.'],
            ['question' => 'Welche Sprachen gibt es?', 'answer' => 'Deutsch (Schweiz), Französisch, Italienisch und Englisch.'],
            ['question' => 'Kostet jede Sprache extra?', 'answer' => 'Nein. Alle vier Sprachen sind in jedem Paket enthalten.'],
        ],
        'related' => ['hochzeitswebsite-erstellen', 'hochzeitseinladung-online', 'preise'],
    ],

    'preise' => [
        'label' => 'Preise',
        'title' => "Preise – Hochzeitswebsite ab {$signature}, einmalig",
        'description' => "Was eine Hochzeitswebsite bei Hereby kostet: kostenlos einrichten, einmalig bezahlen ab {$signature}. Kein Abo, keine Werbung, keine Gebühr pro Gast.",
        'h1' => 'Einmal bezahlen. Kein Abo, keine Werbung.',
        'lede' => 'Ihr richtet eure Hochzeitswebsite kostenlos ein und seht sie in der Vorschau. Bezahlt wird einmal, wenn die persönlichen Links an eure Gäste gehen. Unabhängig davon, wie viele Gäste ihr einladet.',
        'sections' => [
            [
                'title' => 'Drei Pakete',
                'points' => [
                    ['title' => "Signature · {$signature}", 'body' => 'Persönliche Links pro Haushalt, Antwort in 60 Sekunden, vier Sprachen, Erinnerungen per Mail, Listen für Küche und Location, Kalender für eure Gäste.'],
                    ['title' => "Atelier · {$atelier}", 'body' => 'Alles aus Signature, dazu «Der Abend»: Tischplan, Gruppenfotos und Einlagen für die Trauzeugen, der Heimweg-Hinweis und die Fotos eurer Gäste an einem Ort.'],
                    ['title' => "Couture · ab {$couture}", 'body' => 'Alles aus Atelier, dazu Hochzeitszeitung, «Wir fassen nach», eigene Domain und eine Seite, die wir nach euren Wünschen bauen. Persönlich vereinbart.'],
                ],
            ],
            [
                'title' => 'Einzeln dazu',
                'points' => [
                    ['title' => 'Hochzeitszeitung · '.Chf::price('addons.newspaper'), 'body' => 'Eure Freunde schicken Geschichten und Fotos, die Trauzeugen machen die Zeitung.'],
                    ['title' => 'Wir fassen nach · '.Chf::price('addons.chase_service'), 'body' => 'Wir melden uns bei allen, die zwei Wochen vor der Frist noch nicht geantwortet haben.'],
                    ['title' => 'Eigene Domain · '.Chf::price('addons.custom_domain'), 'body' => 'Eure Seite unter eurem Namen, etwa anna-und-luca.ch.'],
                    ['title' => 'Massanfertigung · '.Chf::price('addons.bespoke'), 'body' => 'Ihr schickt uns Wünsche, Farben und Bilder, wir bauen eure Seite.'],
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Gibt es ein Abo?', 'answer' => 'Nein. Jedes Paket kostet einmal. Eure Seite bleibt bis nach der Hochzeit online, ohne laufende Kosten.'],
            ['question' => 'Kostet es mehr, wenn wir viele Gäste einladen?', 'answer' => 'Nein. Der Preis ist derselbe für 30 oder 300 Gäste.'],
            ['question' => 'Wie bezahlen wir?', 'answer' => 'Mit Karte, Apple Pay, Google Pay oder TWINT, in Franken. Ihr erhaltet eine Quittung per Mail.'],
            ['question' => 'Können wir später auf ein grösseres Paket wechseln?', 'answer' => 'Ja. Ihr bezahlt dann nur die Differenz.'],
        ],
        'related' => ['hochzeitswebsite-erstellen', 'hochzeit-rsvp-online', 'fuer-locations'],
    ],

    'fuer-locations' => [
        'label' => 'Für Hochzeitslocations',
        'title' => 'Für Hochzeitslocations – Gästezahlen ohne Nachfragen',
        'description' => 'Hereby für Hochzeitslocations, Fotografen und Planer in der Schweiz: aktuelle Gästezahlen, Menüs und Allergien pro Tisch, ohne Listen per Mail.',
        'h1' => 'Für Locations, die lieber feiern als nachfragen.',
        'lede' => 'Wenn eure Paare mit Hereby planen, kommen Gästezahlen, Menüs und Allergien geordnet bei euch an. Ohne Excel per Mail, ohne Anruf drei Tage vor dem Fest.',
        'sections' => [
            [
                'title' => 'Was ihr seht',
                'points' => [
                    ['title' => 'Alle Hochzeiten nach Datum', 'body' => 'Jede Hochzeit, deren Paar euch verbunden hat, mit Zusagen, Menüs und Antwortfrist.'],
                    ['title' => 'Die Schlussliste', 'body' => 'Menüs, Kinder und Allergien pro Tisch, druckfertig für Küche und Service.'],
                    ['title' => 'Keine Namen, keine Kontakte', 'body' => 'Ihr seht Zahlen, nicht die Gäste. Das schützt die Paare und euch.'],
                ],
            ],
            [
                'title' => 'Einmal eingetragen, für jedes Paar richtig',
                'body' => [
                    'Adresse, Anreise und Zimmer tragt ihr einmal in eurem Profil ein. Verbindet ein Paar euch, steht das gleich auf seiner Hochzeitswebsite, richtig und aktuell.',
                    'Für Fotografinnen und Fotografen: Ihr ladet eure Bilder direkt in die Erinnerungen des Paars. Für Planerinnen und Planer: Ihr seht dieselben Zahlen wie die Location.',
                ],
            ],
            [
                'title' => 'Bei der Besichtigung zeigen',
                'body' => [
                    'Als Showcase-Location zeigt ihr Paaren schon bei der Besichtigung ihre Hochzeitsseite bei euch: zwei Vornamen, ein Datum, und die Seite steht mit eurer Adresse und euren Bildern. Dazu eine eigene Seite, über die Paare eine Besichtigung anfragen.',
                ],
            ],
        ],
        'faq' => [
            ['question' => 'Was kostet Hereby für Locations?', 'answer' => 'Für Partner gibt es Jahrespakete. Schreibt uns an info@hereby.ch, wir zeigen euch Hereby in einer Viertelstunde.'],
            ['question' => 'Wie verbindet sich ein Paar mit uns?', 'answer' => 'Ihr gebt dem Paar euren Code oder Link. Es verbindet euch mit einem Klick und kann das jederzeit wieder lösen.'],
            ['question' => 'Sehen wir die Daten der Gäste?', 'answer' => 'Nein. Ihr seht Zahlen, Menüs und Allergien pro Tisch, aber keine Namen und keine Kontaktdaten.'],
        ],
        'related' => ['gaesteliste-hochzeit', 'hochzeit-rsvp-online', 'preise'],
    ],
];
