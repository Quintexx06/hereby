<?php

namespace App\Http\Middleware;

use App\Models\Wedding;
use App\Support\WeddingLanguages;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * The photo upload link works only for weddings whose plan includes the
 * memories; anyone else finds nothing. The page speaks the guest's browser
 * language when the couple writes in it, otherwise the wedding's main one.
 */
class EnsureWeddingHasMemories
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $wedding = $request->route('wedding');

        if (! $wedding instanceof Wedding || ! $wedding->includesMemories()) {
            abort(404);
        }

        App::setLocale(WeddingLanguages::preferredBy($request, $wedding));

        return $next($request);
    }
}
