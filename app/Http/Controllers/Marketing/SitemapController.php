<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Response;

/**
 * sitemap.xml for search engines: the landing page, the topic pages, the
 * legal pages and the public pages of Showcase venues. Never a couple's
 * or a guest's page.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0', 'lastmod' => self::modified('landing.php')],
            ...array_map(fn (string $slug): array => [
                'loc' => route('topics.show', $slug),
                'priority' => '0.8',
                'lastmod' => self::modified('pages.php'),
            ], TopicPageController::slugs()),
            ...Partner::query()->where('is_showcase', true)->get(['slug', 'updated_at'])->map(fn (Partner $venue): array => [
                'loc' => route('venues.show', $venue->slug),
                'priority' => '0.5',
                'lastmod' => $venue->updated_at?->toDateString(),
            ])->all(),
            ['loc' => route('legal.privacy'), 'priority' => '0.2'],
            ['loc' => route('legal.imprint'), 'priority' => '0.2'],
        ];

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * When a page's copy last changed, from its language file.
     */
    private static function modified(string $file): ?string
    {
        $time = @filemtime(lang_path("de_CH/{$file}"));

        return $time ? date('Y-m-d', $time) : null;
    }

    /**
     * robots.txt: everything public may be crawled; the app, admin and
     * personal links stay out, and the sitemap is announced.
     */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /dashboard',
            'Disallow: /hochzeit/',
            'Disallow: /admin/',
            'Disallow: /partner/',
            'Disallow: /i/',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
