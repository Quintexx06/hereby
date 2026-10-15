<?php

/*
 * Landing page SEO strings and FAQ (Swiss German: no "ß").
 * Shared by the Vue page and the server-rendered meta tags and FAQPage
 * structured data, so the two can never disagree.
 * Keyword research and rationale: docs/marketing/landing-page.md.
 */
use App\Support\Chf;

$signature = Chf::price('plans.signature');
$atelier = Chf::price('plans.atelier');

return [
    'seo' => [
        'title' => 'Hochzeitswebsite Schweiz – persönlich für jeden Gast',
        'description' => 'Hochzeitswebsite aus der Schweiz: ein privater Link pro Haushalt, Zusage in 60 Sekunden, vier Sprachen, kein Passwort. Kostenlos einrichten.',
        'image_alt' => 'Ein Brautpaar unter dem Schleier im Abendlicht',
    ],

    'faq' => [
        [
            'question' => 'Was ist eine Hochzeitswebsite?',
            'answer' => 'Eine Hochzeitswebsite (auch Hochzeitshomepage) bündelt alles, was eure Gäste wissen müssen: Programm, Orte, Anreise und Übernachtung. Und sie sammelt die Zu- und Absagen, das RSVP. Bei Hereby bekommt jeder Haushalt seine eigene Einladung statt einer Seite für alle.',
        ],
        [
            'question' => 'Brauchen unsere Gäste ein Passwort oder eine App?',
            'answer' => 'Nein. Jeder Haushalt erhält einen privaten, persönlichen Link. Wer ihn öffnet, sieht seine Namen, seine Anlässe und seine Sprache. Ohne Passwort, ohne Konto, ohne App. Suchmaschinen indexieren die Einladungen nicht.',
        ],
        [
            'question' => 'Wie schnell können unsere Gäste antworten?',
            'answer' => 'In unter einer Minute auf dem Handy. Die Namen sind schon ausgefüllt, und es wird nur gefragt, was für diesen Haushalt gilt: welche Anlässe, welches Menü, ob Kinder dabei sind.',
        ],
        [
            'question' => 'Können Ziviltrauung und Fest an verschiedenen Tagen stattfinden?',
            'answer' => 'Ja, das ist der Normalfall. Ihr legt beliebig viele Anlässe an, und jeder Haushalt sieht nur die, zu denen er eingeladen ist. Der Apéro für alle, das Abendessen für einen Teil der Gäste.',
        ],
        [
            'question' => 'In welchen Sprachen gibt es die Einladung?',
            'answer' => 'Deutsch mit Schweizer Schreibweise, Französisch, Italienisch und Englisch. Die Sprache wird pro Haushalt festgelegt, die Familie aus dem Tessin liest also Italienisch, die Freunde aus London Englisch.',
        ],
        [
            'question' => 'Wie lange dauert es, eine Hochzeitswebsite zu erstellen?',
            'answer' => 'Rund zehn Minuten: Ihr beantwortet sieben kurze Fragen zu Paar, Datum, Ort, Ablauf, Gästen, Sprachen und Look, und eure Seite steht. Texte und Fotos verfeinert ihr danach, wann ihr wollt. Lieber machen lassen? Mit der Massanfertigung bauen wir eure Seite nach euren Wünschen.',
        ],
        [
            'question' => 'Was kostet eine Hochzeitswebsite bei Hereby?',
            'answer' => "Einrichten ist kostenlos. Bezahlt wird einmal, wenn ihr die Einladungen verschickt: {$signature} für Signature, {$atelier} für Atelier mit Tischplan, Programm und Gästefotos. Kein Abo, kein Preis pro Gast, keine Werbung.",
        ],
        [
            'question' => 'Was passiert mit den Daten unserer Gäste?',
            'answer' => 'Angaben zu Allergien und Unverträglichkeiten speichern wir verschlüsselt. Zwölf Monate nach der Hochzeit werden die Daten eurer Gäste automatisch gelöscht. Eure Gäste sehen nie Werbung oder Angebote Dritter.',
        ],
    ],
];
