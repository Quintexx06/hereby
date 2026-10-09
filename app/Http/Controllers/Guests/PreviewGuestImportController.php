<?php

namespace App\Http\Controllers\Guests;

use App\Actions\Guests\PreviewGuestImport;
use App\Guests\Import\GuestListReader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guests\PreviewGuestImportRequest;
use App\Models\Wedding;
use Illuminate\Http\JsonResponse;
use RuntimeException;

/**
 * Reads a pasted list or file and returns what would be imported. Nothing is
 * saved here: the couple checks the preview first.
 */
class PreviewGuestImportController extends Controller
{
    public function __invoke(PreviewGuestImportRequest $request, Wedding $wedding, GuestListReader $reader, PreviewGuestImport $preview): JsonResponse
    {
        try {
            $households = $request->hasFile('file')
                ? $reader->fromFile($request->file('file'))
                : $reader->fromText((string) $request->input('text'));
        } catch (RuntimeException) {
            return response()->json(['message' => 'Diese Datei können wir nicht lesen. Versucht es als CSV oder fügt die Zellen ein.'], 422);
        }

        return response()->json(['households' => $preview->handle($wedding, $households)]);
    }
}
