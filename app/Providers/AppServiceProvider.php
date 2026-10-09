<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The Hereby team may do anything a couple may, on any wedding (roadmap 1.1).
        Gate::before(fn (User $user): ?bool => $user->is_admin ? true : null);
        Gate::define('admin', fn (User $user): bool => $user->is_admin);

        Schema::defaultStringLength(191);
        $this->configureDefaults();
        $this->configureRateLimiting();
    }

    /**
     * Rate limits for public, token-protected routes.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('invitations', fn (Request $request): Limit => Limit::perMinute(60)->by($request->ip()));
        RateLimiter::for('address-search', fn (Request $request): Limit => Limit::perMinute(30)->by($request->user()?->id ?: $request->ip()));
        RateLimiter::for('inquiries', fn (Request $request): Limit => Limit::perHour(5)->by($request->ip()));
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        // Fail loudly on lazy loading, silently discarded attributes and
        // missing attributes outside production.
        Model::shouldBeStrict(! app()->isProduction());
        Model::automaticallyEagerLoadRelationships();

        // Inertia props are plain objects, not `{ data: … }` API envelopes.
        JsonResource::withoutWrapping();

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
