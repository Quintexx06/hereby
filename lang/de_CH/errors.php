<?php

/* Error pages (resources/views/errors). */
return [
    'home' => 'Zur Startseite',
    'reload' => 'Neu laden',
    '403' => ['title' => 'Hier geht es nicht weiter.', 'body' => 'Dieser Bereich gehört zu einem anderen Konto. Meldet euch mit dem richtigen an, oder fragt, wer euch den Link geschickt hat.'],
    '404' => ['title' => 'Diese Seite gibt es nicht.', 'body' => 'Vielleicht hat sich ein Tippfehler in den Link geschlichen, oder die Seite ist umgezogen. Ist es eine persönliche Einladung, fragt das Brautpaar nach dem Link.'],
    '419' => ['title' => 'Die Seite war zu lange offen.', 'body' => 'Aus Sicherheitsgründen ist sie abgelaufen. Ladet sie neu und versucht es noch einmal.'],
    '429' => ['title' => 'Kurz durchatmen.', 'body' => 'Das waren viele Anfragen in kurzer Zeit. Versucht es in einer Minute noch einmal.'],
    '500' => ['title' => 'Hier ist etwas schiefgelaufen.', 'body' => 'Wir wurden schon benachrichtigt und schauen es uns an. Versucht es in einem Moment noch einmal.'],
    '503' => ['title' => 'Wir sind gleich zurück.', 'body' => 'Hereby wird gerade aktualisiert. In ein paar Minuten ist alles wieder da.'],
];
