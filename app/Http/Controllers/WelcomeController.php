<?php

namespace App\Http\Controllers;

use App\Enums\Locale;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The marketing landing page. Swiss German first (roadmap 0.3); SEO strings
 * and the FAQ come from lang/de_CH/landing.php so the page and the
 * server-rendered structured data share one source.
 */
class WelcomeController extends Controller
{
    public function __invoke(): Response
    {
        App::setLocale(Locale::GermanSwiss->value);

        return Inertia::render('Welcome', [
            'seo' => __('landing.seo'),
            'faq' => __('landing.faq'),
        ]);
    }
}
