<?php

namespace App\Support\Seo;

use Illuminate\Support\Str;

/**
 * Title, description, canonical and share image of a public page, read from
 * its Inertia page so the server can render them before any JavaScript.
 * Every other page (app, sign-in, guests) gets null and stays noindex.
 */
class PageMeta
{
    private const LEGAL = [
        'legal/Privacy' => [
            'title' => 'Datenschutzerklärung',
            'description' => 'Wie Hereby die Daten von Paaren und Gästen bearbeitet, wo sie liegen und wann sie gelöscht werden.',
        ],
        'legal/Imprint' => [
            'title' => 'Impressum',
            'description' => 'Impressum von Hereby, der Hochzeitswebsite aus der Schweiz: Anbieter, Kontakt und Verantwortliche.',
        ],
    ];

    /**
     * @param  array<string, mixed>  $page
     * @return array{title: string, description: string, canonical: string, image: string, image_alt: string, image_width: int|null, image_height: int|null, type: string}|null
     */
    public static function for(array $page): ?array
    {
        $component = (string) ($page['component'] ?? '');
        /** @var array<string, mixed> $props */
        $props = is_array($page['props'] ?? null) ? $page['props'] : [];
        $canonical = request()->routeIs('home') ? route('home') : url()->current();
        $default = [
            'canonical' => $canonical,
            'image' => asset('images/og-image.jpg'),
            'image_alt' => (string) __('landing.seo.image_alt', [], 'de_CH'),
            'image_width' => 1200,
            'image_height' => 630,
            'type' => 'website',
        ];

        if (in_array($component, ['Welcome', 'topics/Show'], true) && is_array($props['seo'] ?? null)) {
            return [...$default, 'title' => (string) $props['seo']['title'], 'description' => (string) $props['seo']['description']];
        }

        if (isset(self::LEGAL[$component])) {
            return [...$default, ...self::LEGAL[$component]];
        }

        if ($component === 'venues/Show' && is_array($props['venue'] ?? null)) {
            return self::venue($props['venue'], $default);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $venue
     * @param  array{canonical: string, image: string, image_alt: string, image_width: int, image_height: int, type: string}  $default
     * @return array{title: string, description: string, canonical: string, image: string, image_alt: string, image_width: int|null, image_height: int|null, type: string}
     */
    private static function venue(array $venue, array $default): array
    {
        $name = (string) ($venue['name'] ?? '');
        $locality = (string) ($venue['locality'] ?? '');
        $photo = is_array($venue['photos'][0] ?? null) ? $venue['photos'][0] : null;
        $about = Str::squish((string) ($venue['about'] ?? ''));

        return [
            ...$default,
            'title' => $locality !== '' ? "{$name} – Hochzeitslocation in {$locality}" : "{$name} – Hochzeitslocation",
            'description' => Str::limit($about !== '' ? $about : "{$name}: heiraten in {$locality}. Anreise, Zimmer und Besichtigung anfragen.", 155),
            'image' => $photo ? (string) $photo['url'] : $default['image'],
            'image_alt' => $photo ? $name : $default['image_alt'],
            'image_width' => $photo ? (int) $photo['width'] : $default['image_width'],
            'image_height' => $photo ? (int) $photo['height'] : $default['image_height'],
        ];
    }
}
