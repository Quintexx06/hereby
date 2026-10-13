<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\ServeConnectedDomains;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;

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

        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
