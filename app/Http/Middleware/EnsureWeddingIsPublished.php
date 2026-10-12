<?php

namespace App\Http\Middleware;

use App\Models\Household;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Personal links open once the couple has paid for a plan. Until then a
 * guest finds nothing; the couple (and the team) can still try their links.
 */
class EnsureWeddingIsPublished
{
    public function handle(Request $request, Closure $next): Response
    {
        $household = $request->route('household');

        if ($household instanceof Household
            && ! $household->wedding->isPublished()
            && ! $request->user()?->can('update', $household->wedding)) {
            abort(404);
        }

        return $next($request);
    }
}
