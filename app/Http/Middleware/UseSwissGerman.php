<?php

namespace App\Http\Middleware;

use App\Enums\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * The platform speaks Swiss German first (landing, sign in, sign up), so
 * validation and auth messages come back in the language of the page.
 */
class UseSwissGerman
{
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale(Locale::GermanSwiss->value);

        return $next($request);
    }
}
