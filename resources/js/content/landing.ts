/**
 * Landing page copy. Kept out of components so marketing text can change
 * without touching markup (and later move to the backend / CMS).
 * TODO(roadmap): replace placeholder positioning once the product brief is final.
 */
export const hero = {
    eyebrow: 'No. 001 — Declarations, kept',
    titleLead: 'Put it',
    titleAccent: 'in writing.',
    lede: 'Hereby turns intentions into clear, signed declarations — and keeps everyone honest about them.',
    primaryCta: 'Start a declaration',
    secondaryCta: 'Sign in',
} as const;

export const principles = {
    eyebrow: 'Articles',
    title: 'Three clauses we never break.',
    items: [
        {
            title: 'Plain language',
            body: 'Every declaration reads like a sentence, not a contract. If it needs a lawyer to parse, it gets rewritten.',
        },
        {
            title: 'Witnessed',
            body: 'Signatures, timestamps and an immutable history make each commitment verifiable after the fact.',
        },
        {
            title: 'Kept, not filed',
            body: 'Reminders, check-ins and a clear status turn a promise on paper into one that gets honoured.',
        },
    ],
} as const;

export const closing = {
    title: 'I, the undersigned, hereby…',
    lede: 'Finish the sentence. It takes a minute.',
    cta: 'Create your account',
} as const;
