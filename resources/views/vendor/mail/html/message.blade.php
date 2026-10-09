@props(['brand' => null])
<x-mail::layout>
{{-- Header --}}
<x-slot:header>
{{-- A guest's email carries the couple's names, never only ours: it must not read like phishing. --}}
<x-mail::header :url="config('app.url')">
{{ $brand ?? config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
{{ __('common.credit') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
