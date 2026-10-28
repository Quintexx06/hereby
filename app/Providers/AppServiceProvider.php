<?php

namespace App\Providers;

use App\Models\User;
use App\Support\InertiaErrorPage;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

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
        Inertia::handleExceptionsUsing(new InertiaErrorPage);

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
        RateLimiter::for('photo-uploads', fn (Request $request): Limit => Limit::perMinute(120)->by($request->ip()));
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

        // Names and notes that couples, guests or visitors type cannot become
        // links or markup in our mails.
        Markdown::withSecuredEncoding();

        // Inertia props are plain objects, not `{ data: … }` API envelopes.
        JsonResource::withoutWrapping();

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        // Length and a leak check, no composition rules (NIST SP 800-63B):
        // the forms say «Mindestens 12 Zeichen», and that is all it takes.
        Password::defaults(fn (): Password => app()->isProduction()
            ? Password::min(12)->uncompromised()
            : Password::min(12),
        );
    }
}
