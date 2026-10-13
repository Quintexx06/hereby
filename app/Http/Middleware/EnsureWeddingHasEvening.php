<?php

namespace App\Http\Middleware;

use App\Models\Household;
use App\Models\Wedding;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * «Der Abend» pages (sign-ups, witness link) exist only for weddings whose
 * plan includes the evening; anyone else finds nothing.
 */
class EnsureWeddingHasEvening
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $wedding = $request->route('wedding');
        $household = $request->route('household');
        $wedding = $household instanceof Household ? $household->wedding : $wedding;

        if (! $wedding instanceof Wedding || ! $wedding->includesEvening()) {
            abort(404);
        }

        return $next($request);
    }
}
