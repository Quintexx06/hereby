<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Before first paint: skip the invitation opening (1.3) when already seen or motion is reduced. --}}
        <script>
            try {
                if (matchMedia('(prefers-reduced-motion: reduce)').matches || localStorage.getItem('hereby:opening:' + location.pathname) === '1') {
                    document.documentElement.dataset.openingSeen = '1';
                }
            } catch (e) {}
        </script>

        {{-- Hereby is always light (porcelain); night only appears as a .stage section. --}}
        <style>
            html {
                background-color: oklch(0.977 0.009 22);
            }
        </style>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <meta name="theme-color" content="#faf3f2" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#100b0b" media="(prefers-color-scheme: dark)">

        {{-- Public pages carry full SEO; everything else (app, sign-in) stays out of search. --}}
        @php($meta = \App\Support\Seo\PageMeta::for($page))
        @if ($meta)
            @include('partials.seo', ['meta' => $meta, 'page' => $page])
        @else
            <meta name="robots" content="noindex, nofollow">
        @endif

        {{--
            Guest pages (rule 2: under two seconds on mobile data): the lean stylesheet,
            inlined so the first response can paint, and the one font file preloaded.
        --}}
        @php($guestPage = str_starts_with($page['component'], 'invitation/'))
        @php($leanGuestPage = $guestPage && ! Vite::isRunningHot())
        {{-- The design's own stylesheet (ADR 0015): a guest page carries only its design. --}}
        @php($guestTemplate = $guestPage ? \App\Enums\WeddingTemplate::fromKey(data_get($page, 'props.invitation.wedding.template', data_get($page, 'props.reply.wedding.template', data_get($page, 'props.wedding.template', data_get($page, 'props.template'))))) : null)
        @if ($leanGuestPage)
            {{--
                The HTML is server-rendered and links work without script, so the app
                only hydrates. It starts after the first frame, leaving the connection
                to the page and its font (rule 2).
            --}}
            <script type="module">
                requestAnimationFrame(() => setTimeout(() => import(@js(Vite::asset('resources/js/app.ts'))), 0));
            </script>
            <style>{!! Vite::content('resources/css/guest.css') !!}</style>
            @if ($guestTemplate)
                <style>{!! Vite::content($guestTemplate->stylesheet()) !!}</style>
            @endif
            {{-- The design's faces (ADR 0015): the names are set in them. --}}
            @foreach ($guestTemplate?->fonts() ?? [] as $guestFont)
                <link rel="preload" href="{{ Vite::asset($guestFont) }}" as="font" type="font/woff2" crossorigin>
            @endforeach
        @endif
        @unless ($leanGuestPage)
            @vite(array_filter([
                $guestPage ? 'resources/css/guest.css' : 'resources/css/app.css',
                $guestTemplate?->stylesheet(),
                'resources/js/app.ts',
                "resources/js/pages/{$page['component']}.vue",
            ]))
        @endunless
        <x-inertia::head>
            <title>{{ $meta ? $meta['title'].' - '.config('app.name') : config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body>
        <x-inertia::app />
    </body>
</html>
