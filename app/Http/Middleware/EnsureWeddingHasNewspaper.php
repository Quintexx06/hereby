<?php

namespace App\Http\Middleware;

use App\Enums\Addon;
use App\Models\Wedding;
use App\Support\WeddingLanguages;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * The newspaper's pages exist only for weddings with the newspaper (an
 * extra, or Couture). Contributors read it in their browser's language.
 */
class EnsureWeddingHasNewspaper
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $wedding = $request->route('wedding');

        if (! $wedding instanceof Wedding || ! $wedding->hasAddon(Addon::Newspaper)) {
            abort(404);
        }

        App::setLocale(WeddingLanguages::preferredBy($request, $wedding));

        return $next($request);
    }
}
