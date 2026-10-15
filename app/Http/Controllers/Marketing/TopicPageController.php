<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\Locale;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The SEO subpages (lang/de_CH/pages.php): one topic and one search intent
 * each, with the same FAQ in the page and in its structured data.
 */
class TopicPageController extends Controller
{
    public function __invoke(string $slug): Response
    {
        App::setLocale(Locale::GermanSwiss->value);

        /** @var array<string, array{label: string, title: string, description: string, h1: string, lede: string, sections: list<array<string, mixed>>, faq: list<array{question: string, answer: string}>, related: list<string>}> $pages */
        $pages = __('pages');
        abort_unless(isset($pages[$slug]), 404);

        $page = $pages[$slug];

        return Inertia::render('topics/Show', [
            'slug' => $slug,
            'page' => $page,
            'seo' => ['title' => $page['title'], 'description' => $page['description'], 'image_alt' => __('landing.seo.image_alt')],
            'faq' => $page['faq'],
            'related' => array_map(fn (string $related): array => [
                'slug' => $related,
                'label' => $pages[$related]['label'],
                'description' => $pages[$related]['description'],
            ], $page['related']),
        ]);
    }

    /**
     * Every topic slug, for routes and the sitemap.
     *
     * @return list<string>
     */
    public static function slugs(): array
    {
        /** @var array<string, mixed> $pages */
        $pages = trans('pages', [], Locale::GermanSwiss->value);

        return array_keys($pages);
    }
}
