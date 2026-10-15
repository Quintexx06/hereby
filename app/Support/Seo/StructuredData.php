<?php

namespace App\Support\Seo;

use App\Enums\Plan;
use App\Support\Chf;

/**
 * The schema.org graph of a public page (docs/marketing/landing-page.md):
 * Organization and WebSite on every page, the page itself with its
 * breadcrumb, and what the page is about (offers, FAQ, a venue). Nodes
 * reference each other by @id, so search engines read one connected graph.
 */
class StructuredData
{
    /**
     * @param  array{title: string, description: string, canonical: string, image: string}  $meta
     * @param  array<string, mixed>  $props  The Inertia page props.
     * @return array<string, mixed>
     */
    public static function graph(string $component, array $meta, array $props): array
    {
        $home = route('home');
        $graph = [self::organization($home), self::website($home), self::webPage($component, $meta, $home)];

        if ($component !== 'Welcome') {
            $graph[] = self::breadcrumb($meta);
        }

        if (in_array($component, ['Welcome', 'topics/Show'], true) && ! empty($props['faq'])) {
            $graph[] = self::faq($props['faq'], $meta['canonical']);
        }

        if ($component === 'Welcome' || ($props['slug'] ?? null) === 'preise') {
            $graph[] = self::product($home);
        }

        if ($component === 'venues/Show' && isset($props['venue']) && is_array($props['venue'])) {
            $graph[] = self::venue($props['venue'], $meta);
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }

    /**
     * @return array<string, mixed>
     */
    private static function organization(string $home): array
    {
        return [
            '@type' => 'Organization',
            '@id' => "{$home}#organization",
            'name' => 'Hereby',
            'url' => $home,
            'logo' => ['@type' => 'ImageObject', 'url' => asset('apple-touch-icon.png'), 'width' => 180, 'height' => 180],
            'email' => 'info@hereby.ch',
            'areaServed' => ['@type' => 'Country', 'name' => 'Schweiz'],
            'knowsLanguage' => ['de-CH', 'fr-CH', 'it-CH', 'en'],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => 'info@hereby.ch',
                'availableLanguage' => ['German', 'French', 'Italian', 'English'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function website(string $home): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => "{$home}#website",
            'url' => $home,
            'name' => 'Hereby',
            'inLanguage' => 'de-CH',
            'publisher' => ['@id' => "{$home}#organization"],
        ];
    }

    /**
     * @param  array{title: string, description: string, canonical: string, image: string}  $meta
     * @return array<string, mixed>
     */
    private static function webPage(string $component, array $meta, string $home): array
    {
        return array_filter([
            '@type' => $component === 'legal/Imprint' ? 'AboutPage' : 'WebPage',
            '@id' => "{$meta['canonical']}#webpage",
            'url' => $meta['canonical'],
            'name' => $meta['title'],
            'description' => $meta['description'],
            'inLanguage' => 'de-CH',
            'isPartOf' => ['@id' => "{$home}#website"],
            'about' => $component === 'Welcome' ? ['@id' => "{$home}#product"] : null,
            'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $meta['image']],
            'breadcrumb' => $component === 'Welcome' ? null : ['@id' => "{$meta['canonical']}#breadcrumb"],
        ]);
    }

    /**
     * @param  array{title: string, description: string, canonical: string, image: string}  $meta
     * @return array<string, mixed>
     */
    private static function breadcrumb(array $meta): array
    {
        return [
            '@type' => 'BreadcrumbList',
            '@id' => "{$meta['canonical']}#breadcrumb",
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Hereby', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $meta['title'], 'item' => $meta['canonical']],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function faq(mixed $items, string $canonical): array
    {
        return [
            '@type' => 'FAQPage',
            '@id' => "{$canonical}#faq",
            'mainEntity' => array_map(fn (array $item): array => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
            ], is_array($items) ? array_values($items) : []),
        ];
    }

    /**
     * Hereby as an app with its three one-time plans, in CHF.
     *
     * @return array<string, mixed>
     */
    private static function product(string $home): array
    {
        $plans = Plan::cases();
        $prices = array_map(fn (Plan $plan): float => $plan->price() / 100, $plans);

        return [
            '@type' => 'SoftwareApplication',
            '@id' => "{$home}#product",
            'name' => 'Hereby Hochzeitswebsite',
            'applicationCategory' => 'LifestyleApplication',
            'operatingSystem' => 'Web',
            'url' => $home,
            'description' => __('landing.seo.description', [], 'de_CH'),
            'inLanguage' => ['de-CH', 'fr-CH', 'it-CH', 'en'],
            'publisher' => ['@id' => "{$home}#organization"],
            'offers' => [
                '@type' => 'AggregateOffer',
                'priceCurrency' => 'CHF',
                'lowPrice' => min($prices),
                'highPrice' => max($prices),
                'offerCount' => count($plans),
                'offers' => array_map(fn (Plan $plan): array => [
                    '@type' => 'Offer',
                    'name' => $plan->label(),
                    'price' => $plan->price() / 100,
                    'priceCurrency' => 'CHF',
                    'description' => "Hereby {$plan->label()}, einmalig ".Chf::format($plan->price()),
                    'url' => route('topics.show', 'preise'),
                    'availability' => 'https://schema.org/InStock',
                ], $plans),
            ],
        ];
    }

    /**
     * A Showcase venue's public page as an EventVenue.
     *
     * @param  array<string, mixed>  $venue
     * @param  array{title: string, description: string, canonical: string, image: string}  $meta
     * @return array<string, mixed>
     */
    private static function venue(array $venue, array $meta): array
    {
        $photos = is_array($venue['photos'] ?? null) ? array_column($venue['photos'], 'url') : [];

        return array_filter([
            '@type' => 'EventVenue',
            '@id' => "{$meta['canonical']}#venue",
            'name' => $venue['name'] ?? null,
            'url' => $meta['canonical'],
            'description' => $venue['about'] ?? null,
            'image' => $photos ?: null,
            'maximumAttendeeCapacity' => $venue['capacity'] ?? null,
            'sameAs' => $venue['website'] ?? null,
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => $venue['address'] ?? null,
                'postalCode' => $venue['postcode'] ?? null,
                'addressLocality' => $venue['locality'] ?? null,
                'addressCountry' => 'CH',
            ]),
        ]);
    }
}
