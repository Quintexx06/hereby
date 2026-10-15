<x-mail::message>
# Hallo {{ $invite->name }}

{{ $couple }} planen ihre Hochzeit mit Hereby und möchten euch dabeihaben. Mit einem kostenlosen Zugang seht ihr {{ $sees }}.

<x-mail::button :url="$link">
Zugang einrichten
</x-mail::button>

Ihr wählt nur ein Passwort, die Hochzeit ist dann schon verbunden. Der Link gilt {{ $days }} Tage.

Herzlich, das Hereby-Team
</x-mail::message>
