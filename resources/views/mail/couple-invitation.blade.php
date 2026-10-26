<x-mail::message>
# Willkommen bei Hereby, {{ $couple }}.

Wir haben eure Hochzeitswebsite angelegt. Wählt ein Passwort, dann seht ihr, was wir vorbereitet haben, und könnt alles ergänzen.

<x-mail::button :url="$link">
Passwort wählen
</x-mail::button>

Der Link gilt sieben Tage. Danach fordert ihr unter «Passwort vergessen» einfach einen neuen an.

Herzlich, das Hereby-Team
</x-mail::message>
