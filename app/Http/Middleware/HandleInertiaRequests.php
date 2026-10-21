<?php

namespace App\Http\Middleware;

use App\Actions\Account\BuildAccountSummary;
use App\Enums\AdminView;
use App\Models\Inquiry;
use App\Models\LogEntry;
use App\Models\Wedding;
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
            // Guest-facing strings (ADR 0006), only where guest pages render: the
            // couple's own pages are German copy in Vue and never need them.
            'translations' => fn (): array => $request->routeIs('invitation.*', 'weddings.preview', 'weddings.guests.preview', 'partner.preview', 'venues.demo', 'venues.demo.reply', 'samples.show', 'samples.reply')
                ? FrontendTranslations::forCurrentLocale()
                : [],
            'auth' => [
                'user' => $request->user(),
            ],
            // The couple's current wedding, for navigation and the sidebar (null for guests and new couples).
            'currentWedding' => fn (): ?array => $this->currentWedding($request),
            // The account pages' side panel (layouts/settings); null everywhere else.
            'account' => fn (): ?array => $request->user() && $request->routeIs('profile.*', 'security.*', 'user-password.*')
                ? app(BuildAccountSummary::class)->handle($request->user())
                : null,
            // Open landing questions, for the admin's sidebar badge; never sent to couples.
            'adminInbox' => fn (): ?int => $request->user()?->is_admin
                ? Inquiry::query()->whereNull('answered_at')->count()
                : null,
            // Team or couple view for admins (null for everyone else), and open errors in the log.
            'adminView' => fn (): ?string => AdminView::for($request)?->value,
            'adminIssues' => fn (): ?int => $request->user()?->is_admin
                ? LogEntry::query()->open()->errors()->count()
                : null,
            // A venue's, photographer's or planner's account, for their own navigation.
            'partner' => fn (): ?array => ($partner = $request->user()?->partner) ? [
                'name' => $partner->name,
                'type' => $partner->type->value,
                'is_showcase' => $partner->is_showcase,
            ] : null,
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * @return array{id: int, status: string, couple_names: string, date: string|null, households: int, setup_step: string|null, plan: string|null, published: bool}|null
     */
    private function currentWedding(Request $request): ?array
    {
        $wedding = AdminView::for($request) === AdminView::Team
            ? $this->routeWedding($request)
            : $request->user()?->weddings()->withCount('households')->first();

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
            'plan' => $wedding->plan?->value,
            'published' => $wedding->isPublished(),
        ];
    }

    /**
     * In the team view an admin has no wedding of their own in the nav;
     * inside a couple's pages they see that couple's navigation.
     */
    private function routeWedding(Request $request): ?Wedding
    {
        $wedding = $request->route('wedding');

        if (! $wedding instanceof Wedding) {
            return null;
        }

        return $wedding->loadCount('households');
    }
}
