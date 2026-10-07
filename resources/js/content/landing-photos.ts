/**
 * Landing photography, self-hosted from public/images/landing (no third-party
 * requests). All from Unsplash under the Unsplash License; credits are shown
 * in the footer and listed in public/images/landing/CREDITS.md.
 */
export type LandingPhoto = {
    src: string;
    srcset?: string;
    portrait?: string;
    width: number;
    height: number;
    alt: string;
    credit: { name: string; url: string };
};

const base = '/images/landing';

export const landingPhotos = {
    heroVeil: {
        src: `${base}/hero-veil-1440.webp`,
        srcset: `${base}/hero-veil-1440.webp 1440w, ${base}/hero-veil-2400.webp 2400w`,
        portrait: `${base}/hero-veil-portrait.webp`,
        width: 1440,
        height: 810,
        alt: 'Ein Brautpaar Stirn an Stirn, der Schleier weht im Abendlicht',
        credit: {
            name: 'Jakob Owens',
            url: 'https://unsplash.com/photos/mLIurLmSRAY',
        },
    },
    veilKiss: {
        src: `${base}/veil-kiss.webp`,
        width: 1000,
        height: 1250,
        alt: 'Braut und Bräutigam küssen sich, vom Schleier umhüllt',
        credit: {
            name: 'Nathan Dumlao',
            url: 'https://unsplash.com/photos/7baHM9rEYUw',
        },
    },
    lakeJetty: {
        src: `${base}/lake-jetty-1200.webp`,
        srcset: `${base}/lake-jetty-1200.webp 1200w, ${base}/lake-jetty-2400.webp 2400w`,
        width: 1200,
        height: 900,
        alt: 'Ein Holzsteg am Vierwaldstättersee vor verschneiten Bergen',
        credit: {
            name: 'C Boyd',
            url: 'https://unsplash.com/photos/Q-AQrJr4LSI',
        },
    },
    tableCandles: {
        src: `${base}/table-candles.webp`,
        width: 1000,
        height: 1250,
        alt: 'Eine lange Hochzeitstafel mit Kerzen und Rosen',
        credit: {
            name: 'Tetiana Thiel',
            url: 'https://unsplash.com/photos/ZueZt8hMdvw',
        },
    },
    bouquet: {
        src: `${base}/bouquet.webp`,
        width: 1000,
        height: 1250,
        alt: 'Ein Brautstrauss im Gegenlicht',
        credit: {
            name: 'Nathan Dumlao',
            url: 'https://unsplash.com/photos/5BB_atDT4oA',
        },
    },
    sparklers: {
        src: `${base}/sparklers-1440.webp`,
        srcset: `${base}/sparklers-1440.webp 1440w, ${base}/sparklers-2400.webp 2400w`,
        portrait: `${base}/sparklers-portrait.webp`,
        width: 1440,
        height: 810,
        alt: 'Das Brautpaar küsst sich zwischen Wunderkerzen der Gäste',
        credit: {
            name: 'Jonathan Borba',
            url: 'https://unsplash.com/photos/omrzI7uCqRo',
        },
    },
} satisfies Record<string, LandingPhoto>;
