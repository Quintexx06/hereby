{{-- The printable kitchen and service sheet (roadmap 1.12). Server-rendered for
     the owner only: it is the one place allergy notes are shown in full. --}}
<!doctype html>
<html lang="de-CH">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Küchenblatt: {{ $wedding->couple_names }}</title>
<style>
    /* A4 paper, night ink on white. Fixed values: this page is printed, not themed. */
    @page { size: A4; margin: 16mm 14mm; }
    * { box-sizing: border-box; }
    body { margin: 0 auto; max-width: 190mm; padding: 24px; font: 11pt/1.45 system-ui, -apple-system, 'Segoe UI', sans-serif; color: #231b18; background: #fff; }
    h1 { font-size: 22pt; margin: 0 0 4px; letter-spacing: -0.02em; }
    h2 { font-size: 13pt; margin: 28px 0 8px; padding-top: 12px; border-top: 1px solid #231b18; }
    p.meta { margin: 0; color: #6b5f5a; }
    table { width: 100%; border-collapse: collapse; }
    th, td { text-align: left; padding: 6px 8px 6px 0; border-bottom: 1px solid #ddd5d2; vertical-align: top; }
    th { font-size: 9pt; font-weight: 600; color: #6b5f5a; }
    td.num { text-align: right; font-variant-numeric: tabular-nums; font-weight: 600; }
    .actions { display: flex; gap: 12px; margin: 16px 0 8px; }
    .actions button { font: inherit; padding: 10px 18px; border-radius: 999px; border: 0; background: #231b18; color: #fff; cursor: pointer; }
    .event { break-inside: avoid; }
    @media print { .actions { display: none; } body { padding: 0; } }
</style>
</head>
<body>
    <h1>{{ $wedding->couple_names }}</h1>
    <p class="meta">Küchen- und Serviceblatt, Stand {{ now('Europe/Zurich')->format('d.m.Y, H:i') }} Uhr{{ $wedding->wedding_date ? ', Hochzeit am '.$wedding->wedding_date->format('d.m.Y') : '' }}</p>
    <div class="actions"><button type="button" onclick="window.print()">Drucken oder als PDF sichern</button></div>

    @foreach ($summary['events'] as $event)
        <section class="event">
            <h2>{{ $eventName($event) }}, {{ \Illuminate\Support\Carbon::parse($event['starts_at'])->timezone('Europe/Zurich')->format('d.m.Y, H:i') }}</h2>
            <table>
                <tr><td>Personen</td><td class="num">{{ $event['attending'] }}</td></tr>
                <tr><td>davon Kinder</td><td class="num">{{ $event['children'] }}</td></tr>
                <tr><td>Noch keine Antwort</td><td class="num">{{ $event['pending'] }}</td></tr>
                @foreach ($event['menus'] as $menu)
                    <tr><td>{{ $menu['label'] }}</td><td class="num">{{ $menu['count'] }}</td></tr>
                @endforeach
            </table>
        </section>
    @endforeach

    <h2>Allergien und Unverträglichkeiten</h2>
    @if ($allergies)
        <table>
            <tr><th>Name</th><th>Haushalt</th><th>Dabei bei</th><th>Angabe</th></tr>
            @foreach ($allergies as $row)
                <tr>
                    <td>{{ $row['name'] }}{{ $row['child'] ? ' (Kind)' : '' }}</td>
                    <td>{{ $row['household'] }}</td>
                    <td>{{ implode(', ', $row['parts']) ?: '–' }}</td>
                    <td>{{ $row['notes'] }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Keine Angaben.</p>
    @endif

    @if ($summary['shuttle'] || $summary['stays'] || $summary['songs'])
        <h2>Service</h2>
        <table>
            <tr><td>Plätze im Shuttle</td><td class="num">{{ $summary['shuttle'] }}</td></tr>
            <tr><td>Haushalte mit Übernachtung</td><td class="num">{{ $summary['stays'] }}</td></tr>
        </table>
        @if ($summary['songs'])
            <h2>Liederwünsche</h2>
            <ul>
                @foreach ($summary['songs'] as $song)
                    <li>{{ $song }}</li>
                @endforeach
            </ul>
        @endif
    @endif
</body>
</html>
