<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\ServeConnectedDomains;
use App\Http\Middleware\SetSecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['sidebar_state']);

        // Behind Coolify's proxy (Traefik/Caddy): trust its X-Forwarded-* headers so
        // URLs, signed links (reminder opt-out) and secure cookies use https.
        $middleware->trustProxies(at: '*');

        // A couple's own domain serves only its guests' pages (spec 2026-10-13).
        $middleware->append(ServeConnectedDomains::class);
        $middleware->append(SetSecurityHeaders::class);

        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // An old link from a mail: say so, and offer a fresh one instead of a 403.
        $exceptions->render(fn (InvalidSignatureException $exception, Request $request) => match (true) {
            $request->routeIs('verification.verify') => to_route('verification.notice')->with('status', 'link-expired'),
            $request->routeIs('welcome-link') => to_route('password.request')->with('status', 'Der Link aus der Willkommensmail ist abgelaufen. Gebt eure E-Mail-Adresse ein, dann schicken wir euch einen neuen.'),
            default => null,
        });
    })->create();
