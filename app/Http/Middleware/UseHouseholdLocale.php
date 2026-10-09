<?php

namespace App\Http\Middleware;

use App\Models\Household;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guest pages speak the household's language, validation messages included
 * (ADR 0006). Runs after route binding, so the household is already loaded.
 */
class UseHouseholdLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $household = $request->route('household');

        if ($household instanceof Household) {
            App::setLocale($household->locale->value);
        }

        return $next($request);
    }
}
