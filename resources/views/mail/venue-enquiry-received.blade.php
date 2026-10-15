<x-mail::message>
# Jemand möchte euch besuchen

**{{ $enquiry->name }}** ({{ $enquiry->email }}) hat über eure Seite bei Hereby eine Besichtigung angefragt.
@if ($enquiry->preferred_date)

**Wunschtermin für die Hochzeit:** {{ $enquiry->preferred_date->locale('de_CH')->isoFormat('D. MMMM YYYY') }}
@endif
@if ($enquiry->message)

<x-mail::panel>
{{ $enquiry->message }}
</x-mail::panel>
@endif

Einfach auf diese E-Mail antworten, die Antwort geht direkt an {{ $enquiry->name }}.
</x-mail::message>
