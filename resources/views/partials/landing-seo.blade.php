{{--
    Marketing SEO (landing and topic pages), rendered on the server so
    crawlers and link previews never depend on JavaScript. `data-inertia`
    keys let the client <Head> adopt and later remove these tags on navigation.
--}}
@php
    $isHome = request()->routeIs('home');
    $canonical = $isHome ? route('home') : url()->current();
    $structuredData = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $isHome ? [
                '@type' => 'ProfessionalService',
                'name' => config('app.name'),
                'url' => route('home'),
                'description' => $seo['description'],
                'areaServed' => ['@type' => 'Country', 'name' => 'Schweiz'],
                'availableLanguage' => ['de-CH', 'fr', 'it', 'en'],
                'priceRange' => 'ab '.\App\Support\Chf::price('plans.signature'),
            ] : [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => config('app.name'), 'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $seo['title'], 'item' => $canonical],
                ],
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn (array $item): array => [
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
                ], $faq),
            ],
        ],
    ];
@endphp
<meta data-inertia="description" name="description" content="{{ $seo['description'] }}">
<link data-inertia="canonical" rel="canonical" href="{{ $canonical }}">
<meta data-inertia="og:type" property="og:type" content="website">
<meta data-inertia="og:locale" property="og:locale" content="de_CH">
<meta data-inertia="og:site_name" property="og:site_name" content="{{ config('app.name') }}">
<meta data-inertia="og:title" property="og:title" content="{{ $seo['title'] }}">
<meta data-inertia="og:description" property="og:description" content="{{ $seo['description'] }}">
<meta data-inertia="og:url" property="og:url" content="{{ $canonical }}">
<meta data-inertia="og:image" property="og:image" content="{{ asset('images/og-image.jpg') }}">
<meta data-inertia="og:image:width" property="og:image:width" content="1200">
<meta data-inertia="og:image:height" property="og:image:height" content="630">
<meta data-inertia="og:image:alt" property="og:image:alt" content="{{ $seo['image_alt'] }}">
<meta data-inertia="twitter:card" name="twitter:card" content="summary_large_image">
@if ($isHome)
<link rel="preload" as="image" type="image/webp" href="{{ asset('images/landing/hero-veil-portrait.webp') }}" media="(max-width: 767px)" fetchpriority="high">
<link rel="preload" as="image" type="image/webp" imagesrcset="{{ asset('images/landing/hero-veil-1440.webp') }} 1440w, {{ asset('images/landing/hero-veil-2400.webp') }} 2400w" imagesizes="100vw" media="(min-width: 768px)" fetchpriority="high">
@endif
<script type="application/ld+json">@json($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
