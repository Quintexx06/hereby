<?php

namespace App\Http\Controllers;

use App\Services\Geo\SwissAddressSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Address suggestions for the venue field in the setup.
 */
class AddressSearchController extends Controller
{
    public function __invoke(Request $request, SwissAddressSearch $search): JsonResponse
    {
        $query = $request->string('q')->limit(120, '')->toString();

        return response()->json(['results' => $search->search($query)]);
    }
}
