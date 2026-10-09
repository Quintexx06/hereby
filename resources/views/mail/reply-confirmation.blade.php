<x-mail::message :brand="$couple">
# {{ __('rsvp.mail.heading', ['couple' => $couple]) }}

{{ __('rsvp.mail.intro') }}

@foreach ($events as $event)
**{{ $event['name'] }}**<br>
{{ $event['people'] ? implode(', ', $event['people']) : __('rsvp.mail.nobody') }}

@endforeach
@if ($allergies)
{{ __('rsvp.mail.allergies') }}

@endif
<x-mail::button :url="$link">
{{ __('rsvp.change') }}
</x-mail::button>

@if ($deadline)
{{ __('rsvp.change_until', ['date' => $deadline]) }}
@endif

{{ __('rsvp.mail.calendar') }}

{{ __('rsvp.mail.signoff', ['couple' => $couple]) }}
</x-mail::message>
