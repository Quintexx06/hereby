<x-mail::message :brand="$couple">
# {{ __('rsvp.reminder.heading') }}

{{ __('rsvp.reminder.body', ['couple' => $couple, 'date' => $deadline]) }}

<x-mail::button :url="$link">
{{ __('rsvp.cta') }}
</x-mail::button>

{{ __('rsvp.mail.signoff', ['couple' => $couple]) }}

<x-slot:subcopy>
{{ __('rsvp.reminder.stop_hint') }} [{{ __('rsvp.reminder.stop') }}]({{ $stop }})
</x-slot:subcopy>
</x-mail::message>
