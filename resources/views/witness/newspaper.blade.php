{{-- The wedding newspaper, printed from the witnesses' link (spec 2026-10-13).
     A4, two columns, the couple's names as the masthead. --}}
<!doctype html>
<html lang="de-CH">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Hochzeitszeitung: {{ $wedding->couple_names }}</title>
<style>
    /* Newsprint: night ink on white, a heavy masthead, two columns. Printed, not themed. */
    @page { size: A4; margin: 14mm 12mm; }
    * { box-sizing: border-box; }
    body { margin: 0 auto; max-width: 190mm; padding: 24px; font: 10.5pt/1.5 system-ui, -apple-system, 'Segoe UI', sans-serif; color: #231b18; background: #fff; }
    header { text-align: center; border-bottom: 3px double #231b18; padding-bottom: 10px; margin-bottom: 18px; }
    .kicker { font: 600 8.5pt/1 system-ui, sans-serif; letter-spacing: 0.2em; text-transform: uppercase; margin: 0 0 8px; }
    h1 { font: 800 34pt/1 system-ui, -apple-system, 'Segoe UI', sans-serif; letter-spacing: -0.03em; margin: 0; }
    .dateline { font: 9pt/1.4 system-ui, sans-serif; margin: 8px 0 0; color: #6b5f5a; }
    main { column-count: 2; column-gap: 9mm; }
    article { break-inside: avoid; margin: 0 0 16px; padding-bottom: 12px; border-bottom: 1px solid #ddd5d2; }
    h2 { font: 700 15pt/1.15 system-ui, -apple-system, 'Segoe UI', sans-serif; letter-spacing: -0.01em; margin: 0 0 4px; }
    .byline { font: 9pt/1.3 system-ui, sans-serif; color: #6b5f5a; margin: 0 0 8px; }
    img { width: 100%; height: auto; margin: 0 0 8px; }
    p { margin: 0 0 6px; white-space: pre-line; }
    .actions { display: flex; gap: 12px; margin: 0 0 16px; }
    .actions button { font: 11pt system-ui, sans-serif; padding: 10px 18px; border-radius: 999px; border: 0; background: #231b18; color: #fff; cursor: pointer; }
    @media print { .actions { display: none; } body { padding: 0; } }
</style>
</head>
<body>
    <div class="actions"><button type="button" onclick="window.print()">Drucken oder als PDF sichern</button></div>
    <header>
        <p class="kicker">Die Hochzeitszeitung</p>
        <h1>{{ $wedding->couple_names }}</h1>
        <p class="dateline">{{ $wedding->wedding_date ? $wedding->wedding_date->locale('de_CH')->isoFormat('dddd, D. MMMM YYYY') : '' }}{{ $wedding->venue_town ? ' · '.$wedding->venue_town : '' }}</p>
    </header>

    <main>
        @forelse ($articles as $article)
            <article>
                <h2>{{ $article->title }}</h2>
                <p class="byline">von {{ $article->author_name }}</p>
                @if ($url = $photoUrl($article))
                    <img src="{{ $url }}" alt="">
                @endif
                <p>{{ $article->body }}</p>
            </article>
        @empty
            <p>Noch keine Geschichten.</p>
        @endforelse
    </main>
</body>
</html>
