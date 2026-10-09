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

        @if (($page['component'] ?? null) === 'Welcome')
            @include('partials.landing-seo', ['seo' => $page['props']['seo'], 'faq' => $page['props']['faq']])
        @endif

        {{--
            Guest pages (rule 2: under two seconds on mobile data): the lean stylesheet,
            inlined so the first response can paint, and the one font file preloaded.
        --}}
        @php($leanGuestPage = str_starts_with($page['component'], 'invitation/') && ! Vite::isRunningHot())
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
            <link rel="preload" href="{{ Vite::asset('node_modules/@fontsource-variable/archivo/files/archivo-latin-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
        @endif
        @unless ($leanGuestPage)
            @vite([
                str_starts_with($page['component'], 'invitation/') ? 'resources/css/guest.css' : 'resources/css/app.css',
                'resources/js/app.ts',
                "resources/js/pages/{$page['component']}.vue",
            ])
        @endunless
        <x-inertia::head>
            <title>{{ isset($page['props']['seo']['title']) ? $page['props']['seo']['title'].' - '.config('app.name') : config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body>
        <x-inertia::app />
    </body>
</html>
