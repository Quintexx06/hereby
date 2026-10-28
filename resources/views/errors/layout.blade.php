{{--
    Hereby's error pages: self-contained (no Vite, no JavaScript), so they
    render even when the app itself is what broke. Guests keep their
    language; everywhere else the platform speaks Swiss German.
--}}
@php
    // A guest link that no longer opens (404) never set the household's language: the browser's.
    if (request()->is('i/*') && app()->getLocale() === config('app.locale')) {
        $preferred = request()->getPreferredLanguage(['de', 'fr', 'it', 'en']);
        app()->setLocale($preferred === 'de' || $preferred === null ? 'de_CH' : $preferred);
    }
    if (! in_array(app()->getLocale(), ['de_CH', 'fr', 'it', 'en'], true) || (app()->getLocale() === 'en' && ! request()->is('i/*'))) {
        app()->setLocale('de_CH');
    }
    $copy = __("errors.{$code}");
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>{{ $copy['title'] }} - Hereby</title>
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <style>
            :root { color-scheme: dark; }
            * { box-sizing: border-box; }
            body { margin: 0; min-height: 100svh; display: grid; place-items: center; padding: 24px;
                background: oklch(0.16 0.008 15); color: oklch(0.977 0.009 22);
                font-family: 'Archivo Variable', Archivo, ui-sans-serif, system-ui, sans-serif; }
            main { width: 100%; max-width: 34rem; }
            .mark { font-weight: 700; font-size: 1.5rem; letter-spacing: -0.03em; text-decoration: none; color: inherit; }
            .mark span { color: oklch(0.8 0.09 7); }
            .code { margin: 3.5rem 0 0.75rem; font-size: 0.875rem; color: oklch(0.8 0.024 16); font-variant-numeric: tabular-nums; }
            h1 { margin: 0; font-size: clamp(2rem, 7vw, 3.25rem); line-height: 1; letter-spacing: -0.04em; font-weight: 650; text-wrap: balance; hyphens: auto; }
            p { margin: 1.25rem 0 2.25rem; font-size: 1.0625rem; line-height: 1.55; color: oklch(0.885 0.02 18); text-wrap: pretty; }
            .actions { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; align-items: center; }
            .button { display: inline-flex; align-items: center; height: 3rem; padding: 0 1.5rem; border-radius: 999px;
                background: oklch(0.977 0.009 22); color: oklch(0.16 0.008 15); font-weight: 600; text-decoration: none; }
            .link { color: inherit; text-underline-offset: 0.3em; text-decoration-color: oklch(0.977 0.009 22 / 0.35); }
            .button:focus-visible, .link:focus-visible { outline: 2px solid oklch(0.8 0.09 7); outline-offset: 3px; }
        </style>
    </head>
    <body>
        <main>
            <a href="/" class="mark">hereby<span>.</span></a>
            <p class="code">{{ $code }}</p>
            <h1>{{ $copy['title'] }}</h1>
            <p>{{ $copy['body'] }}</p>
            <div class="actions">
                <a href="/" class="button">{{ __('errors.home') }}</a>
                @if (in_array($code, [419, 429, 500, 503], true))
                    <a href="{{ url()->current() }}" class="link">{{ __('errors.reload') }}</a>
                @endif
            </div>
        </main>
    </body>
</html>
