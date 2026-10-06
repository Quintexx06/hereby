/**
 * Motion tokens for JS animation (GSAP). Mirrors resources/css/theme/motion.css.
 * Rules of thumb (Emil Kowalski):
 *  - UI motion stays under ~300ms; marketing/hero choreography may go longer.
 *  - Enter with ease-out, move with ease-in-out, never ease-in for UI.
 *  - Never animate from scale(0) — start at ~0.95 with opacity.
 */
export const easing = {
    out: 'M0,0 C0.23,1 0.32,1 1,1',
    inOut: 'M0,0 C0.77,0 0.175,1 1,1',
    drawer: 'M0,0 C0.32,0.72 0,1 1,1',
} as const;

export const duration = {
    instant: 0.1,
    fast: 0.16,
    base: 0.22,
    slow: 0.32,
    hero: 0.9,
} as const;

export const stagger = {
    tight: 0.03,
    base: 0.06,
    loose: 0.12,
} as const;

export function prefersReducedMotion(): boolean {
    return (
        typeof window !== 'undefined' &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches
    );
}
