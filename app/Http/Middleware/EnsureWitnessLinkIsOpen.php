<?php

namespace App\Http\Middleware;

use App\Enums\Addon;
use App\Models\Wedding;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The witness link opens with the evening (Atelier and up) or with the
 * newspaper; the parts inside are gated on their own.
 */
class EnsureWitnessLinkIsOpen
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $wedding = $request->route('wedding');

        if (! $wedding instanceof Wedding || (! $wedding->includesEvening() && ! $wedding->hasAddon(Addon::Newspaper))) {
            abort(404);
        }

        return $next($request);
    }
}
