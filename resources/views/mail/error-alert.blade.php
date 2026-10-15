<x-mail::message>
# Ein Fehler auf hereby.ch

**{{ $entry->message }}**

- Stufe: {{ $entry->level }}
@if ($entry->location)
- Ort im Code: {{ $entry->location }}
@endif
@if ($entry->url)
- Anfrage: {{ $entry->url }}
@endif
- Bisher {{ $entry->count }}× gesehen, zuletzt {{ $entry->last_seen_at->timezone('Europe/Zurich')->format('d.m.Y H:i') }}

<x-mail::button :url="$link">
Protokoll öffnen
</x-mail::button>

Tritt er wieder auf, kommt höchstens eine Mail pro Stunde.
</x-mail::message>
