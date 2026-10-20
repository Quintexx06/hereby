<?php

namespace App\Http\Middleware;

use App\Models\Household;
use App\Models\Wedding;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * On a couple's own domain (spec 2026-10-13) only that wedding's personal
 * links answer; Hereby's own pages, and other weddings' guests, are 404.
 * Requests on Hereby's domain pass untouched. In production any other host
 * is 404 too: links in mails (password reset) are built from the request's
 * host, so a forged Host or X-Forwarded-Host must never reach the app.
 */
class ServeConnectedDomains
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        $own = parse_url((string) config('app.url'), PHP_URL_HOST);

        if ($host === $own || $host === "www.{$own}" || $this->isInternal($request)) {
            return $next($request);
        }

        $wedding = Wedding::query()
            ->where('custom_domain', $host)
            ->whereNotNull('custom_domain_verified_at')
            ->first(['id']);

        if ($wedding === null) {
            abort_if(app()->isProduction(), 404);

            return $next($request);
        }

        // Runs before routing (and before any login redirect), so the
        // household is looked up from the path: /i/{token}/…
        $token = $request->segment(1) === 'i' ? (string) $request->segment(2) : '';
        $belongs = $token !== '' && Household::query()
            ->where('token', $token)
            ->where('wedding_id', $wedding->id)
            ->exists();

        if (! $belongs) {
            abort(404);
        }

        return $next($request);
    }

    /**
     * The container's own health checks, which call it as localhost.
     */
    private function isInternal(Request $request): bool
    {
        return in_array($request->getHost(), ['localhost', '127.0.0.1'], true)
            || $request->is('up');
    }
}
