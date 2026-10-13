{{-- The evening's running order for the MC and the band (spec 2026-10-13).
     Printed from the witnesses' private link; accepted offers only. --}}
<!doctype html>
<html lang="de-CH">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Ablauf: {{ $wedding->couple_names }}</title>
<style>
    /* A4 paper, night ink on white. Fixed values: this page is printed, not themed. */
    @page { size: A4; margin: 16mm 14mm; }
    * { box-sizing: border-box; }
    body { margin: 0 auto; max-width: 190mm; padding: 24px; font: 11pt/1.45 system-ui, -apple-system, 'Segoe UI', sans-serif; color: #231b18; background: #fff; }
    h1 { font-size: 22pt; margin: 0 0 4px; letter-spacing: -0.02em; }
    p.meta { margin: 0 0 20px; color: #6b5f5a; }
    table { width: 100%; border-collapse: collapse; }
    th, td { text-align: left; padding: 8px 8px 8px 0; border-bottom: 1px solid #ddd5d2; vertical-align: top; }
    th { font-size: 9pt; font-weight: 600; color: #6b5f5a; }
    td.num { text-align: right; font-variant-numeric: tabular-nums; font-weight: 600; white-space: nowrap; }
    .actions { display: flex; gap: 12px; margin: 16px 0 8px; }
    .actions button { font: inherit; padding: 10px 18px; border-radius: 999px; border: 0; background: #231b18; color: #fff; cursor: pointer; }
    @media print { .actions { display: none; } body { padding: 0; } }
</style>
</head>
<body>
    <h1>{{ $wedding->couple_names }}</h1>
    <p class="meta">Ablauf der Einlagen{{ $wedding->wedding_date ? ', '.$wedding->wedding_date->format('d.m.Y') : '' }} · {{ $total }} Minuten{{ $wedding->programme_minutes ? ' von '.$wedding->programme_minutes : '' }}</p>
    <div class="actions"><button type="button" onclick="window.print()">Drucken oder als PDF sichern</button></div>

    @if ($items->isNotEmpty())
        <table>
            <tr><th>#</th><th>Einlage</th><th>Von</th><th>Braucht</th><th>Min.</th></tr>
            @foreach ($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->title }}</strong><br>{{ $item->kind->label() }}</td>
                    <td>{{ $item->contact_name }}</td>
                    <td>{{ $item->notes ?? '–' }}</td>
                    <td class="num">{{ $item->minutes }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Noch keine angenommenen Einlagen.</p>
    @endif
</body>
</html>
