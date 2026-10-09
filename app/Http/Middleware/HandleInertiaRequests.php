<?php

namespace App\Http\Middleware;

use App\Support\FrontendTranslations;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'locale' => fn (): string => app()->getLocale(),
            'translations' => fn (): array => FrontendTranslations::forCurrentLocale(),
            'auth' => [
                'user' => $request->user(),
            ],
            // The couple's current wedding, for navigation and the sidebar (null for guests and new couples).
            'currentWedding' => fn (): ?array => $this->currentWedding($request),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * @return array{id: int, status: string, couple_names: string, date: string|null, households: int, setup_step: string|null}|null
     */
    private function currentWedding(Request $request): ?array
    {
        $wedding = $request->user()?->weddings()->withCount('households')->first();

        if (! $wedding) {
            return null;
        }

        return [
            'id' => $wedding->id,
            'status' => $wedding->status->value,
            'couple_names' => $wedding->couple_names,
            'date' => $wedding->wedding_date?->toDateString(),
            'households' => (int) $wedding->households_count,
            'setup_step' => $wedding->setup_step?->value,
        ];
    }
}
