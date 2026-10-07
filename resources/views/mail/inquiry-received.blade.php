<x-mail::message>
# Neue Frage von der Website

**Von:** {{ $inquiry->email }}

<x-mail::panel>
{{ $inquiry->question }}
</x-mail::panel>

Einfach auf diese E-Mail antworten, die Antwort geht direkt an das Paar.
</x-mail::message>
