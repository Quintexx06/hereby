<?php

namespace App\Http\Controllers;

use App\Enums\Addon;
use App\Enums\Locale;
use App\Enums\Plan;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The marketing landing page. Swiss German first (roadmap 0.3); SEO strings
 * and the FAQ come from lang/de_CH/landing.php so the page and the
 * server-rendered structured data share one source. Prices come from
 * config/hereby.php, the same as checkout charges, in whole francs.
 */
class WelcomeController extends Controller
{
    public function __invoke(): Response
    {
        App::setLocale(Locale::GermanSwiss->value);

        return Inertia::render('Welcome', [
            'seo' => __('landing.seo'),
            'faq' => __('landing.faq'),
            'prices' => [
                'plans' => collect(Plan::cases())->mapWithKeys(fn (Plan $plan): array => [$plan->value => intdiv($plan->price(), 100)])->all(),
                'addons' => collect(Addon::cases())->mapWithKeys(fn (Addon $addon): array => [$addon->value => intdiv($addon->price(), 100)])->all(),
            ],
        ]);
    }
}
