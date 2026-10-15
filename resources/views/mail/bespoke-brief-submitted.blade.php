<x-mail::message>
# Neues Briefing: {{ $brief->wedding->couple_names }}

@if ($brief->ready_by)
**Fertig bis:** {{ $brief->ready_by->locale('de_CH')->isoFormat('D. MMMM YYYY') }}

@endif
<x-mail::panel>
{{ $brief->wishes }}
</x-mail::panel>

<x-mail::button :url="$link">
Briefing öffnen
</x-mail::button>
</x-mail::message>
