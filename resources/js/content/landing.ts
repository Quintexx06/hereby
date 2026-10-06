/**
 * Landing page copy. Kept out of components so marketing text can change
 * without touching markup (and later move to lang/ for the German launch).
 * Positioning: docs/product/vision.md.
 */
export const hero = {
    eyebrow: 'Wedding websites · Made in Switzerland',
    titleLead: 'We, hereby,',
    titleAccent: 'invite you.',
    lede: 'Art-directed wedding sites with a personal link for every household, and your venue built in. Set up for you within 48 hours.',
    primaryCta: 'Request a demo',
    secondaryCta: 'Sign in',
    signatureCaption: 'Signature of the couple',
} as const;

export const principles = {
    eyebrow: 'Why Hereby',
    title: 'Three promises, kept.',
    items: [
        {
            title: 'Design is the product',
            body: 'Every site is art-directed: typography, motion and photos graded to one look. Nothing ships with a visible bug.',
        },
        {
            title: 'A personal site for every guest',
            body: 'One private link per household. Their names, their events, their language. Answering takes under a minute on a phone.',
        },
        {
            title: 'The venue is built in',
            body: 'Floor plans, menus, rooms and transport come preloaded, and the venue receives final numbers without a single email.',
        },
    ],
} as const;

export const closing = {
    title: 'Planning a summer 2027 wedding?',
    lede: 'Twenty minutes with us, and your site is ready within 48 hours.',
    cta: 'Request a demo',
} as const;
