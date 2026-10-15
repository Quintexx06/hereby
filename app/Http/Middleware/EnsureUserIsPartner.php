<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use App\Models\Wedding;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The partner area is for venue, photographer and planner accounts only,
 * and a `{wedding}` in it only for weddings whose couple connected them.
 */
class EnsureUserIsPartner
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $partner = $request->user()?->partner;
        abort_unless($partner instanceof Partner, 403);

        $wedding = $request->route('wedding');
        if ($wedding instanceof Wedding) {
            abort_unless($partner->weddings()->whereKey($wedding->id)->exists(), 403);
        }

        return $next($request);
    }
}
