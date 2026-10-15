{{--
    Public pages' SEO, rendered on the server so crawlers and link previews
    never depend on JavaScript (docs/marketing/landing-page.md). `data-inertia`
    keys let the client <Head> adopt and later remove these tags on navigation.
--}}
<meta data-inertia="description" name="description" content="{{ $meta['description'] }}">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
<link data-inertia="canonical" rel="canonical" href="{{ $meta['canonical'] }}">
<link rel="alternate" hreflang="de-CH" href="{{ $meta['canonical'] }}">
<link rel="alternate" hreflang="x-default" href="{{ $meta['canonical'] }}">
<meta data-inertia="og:type" property="og:type" content="{{ $meta['type'] }}">
<meta data-inertia="og:locale" property="og:locale" content="de_CH">
<meta data-inertia="og:site_name" property="og:site_name" content="{{ config('app.name') }}">
<meta data-inertia="og:title" property="og:title" content="{{ $meta['title'] }}">
<meta data-inertia="og:description" property="og:description" content="{{ $meta['description'] }}">
<meta data-inertia="og:url" property="og:url" content="{{ $meta['canonical'] }}">
<meta data-inertia="og:image" property="og:image" content="{{ $meta['image'] }}">
@if ($meta['image_width'] && $meta['image_height'])
<meta data-inertia="og:image:width" property="og:image:width" content="{{ $meta['image_width'] }}">
<meta data-inertia="og:image:height" property="og:image:height" content="{{ $meta['image_height'] }}">
@endif
<meta data-inertia="og:image:alt" property="og:image:alt" content="{{ $meta['image_alt'] }}">
<meta data-inertia="twitter:card" name="twitter:card" content="summary_large_image">
<meta data-inertia="twitter:title" name="twitter:title" content="{{ $meta['title'] }}">
<meta data-inertia="twitter:description" name="twitter:description" content="{{ $meta['description'] }}">
<meta data-inertia="twitter:image" name="twitter:image" content="{{ $meta['image'] }}">
@if ($page['component'] === 'Welcome')
<link rel="preload" as="image" type="image/webp" href="{{ asset('images/landing/hero-veil-portrait.webp') }}" media="(max-width: 767px)" fetchpriority="high">
<link rel="preload" as="image" type="image/webp" imagesrcset="{{ asset('images/landing/hero-veil-1440.webp') }} 1440w, {{ asset('images/landing/hero-veil-2400.webp') }} 2400w" imagesizes="100vw" media="(min-width: 768px)" fetchpriority="high">
@endif
<script type="application/ld+json">@json(\App\Support\Seo\StructuredData::graph($page['component'], $meta, $page['props']), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
