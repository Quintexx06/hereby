<x-mail::message :brand="$couple">
# {{ __('evening.way_home.heading') }}

{{ __('evening.way_home.body', ['label' => $departure['label'], 'time' => $departure['time']]) }}
@if ($departure['place'])

{{ __('evening.way_home.from', ['place' => $departure['place']]) }}
@endif

<x-mail::button :url="$link">
{{ __('evening.way_home.cta') }}
</x-mail::button>

{{ __('rsvp.mail.signoff', ['couple' => $couple]) }}
</x-mail::message>
