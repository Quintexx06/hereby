<x-mail::message :brand="$couple">
# {{ __('evening.group_photo.heading') }}

{{ __('evening.group_photo.body', ['title' => $title]) }}
@if ($place)

{{ __('evening.group_photo.place', ['place' => $place]) }}
@endif

{{ __('rsvp.mail.signoff', ['couple' => $couple]) }}
</x-mail::message>
