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

        $languages = WeddingLanguages::of($wedding);
        $short = array_combine(array_map(fn (string $locale): string => substr($locale, 0, 2), $languages), $languages);
        App::setLocale($short[(string) $request->getPreferredLanguage(array_keys($short))] ?? $wedding->default_locale->value);

        return $next($request);
    }
}
