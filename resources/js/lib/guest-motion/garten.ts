import type * as Kit from '@/lib/gsap';
import { sharedMotion } from '@/lib/guest-motion/guest';

/**
 * Garten: the branches sway as the hero scrolls away, each arch opens from
 * the ground up like a gate, and the programme's leaves unfurl in turn.
 */
export function gartenMotion(kit: typeof Kit, root: HTMLElement) {
    const { gsap } = kit;
    sharedMotion(kit, root);

    root.querySelectorAll<SVGElement>('.garten-branch').forEach((branch) => {
        const left = branch.dataset.side === 'left';
        gsap.to(branch, {
            rotate: left ? -6 : 6,
            yPercent: -8,
            ease: 'none',
            transformOrigin: left ? '0% 100%' : '100% 100%',
            scrollTrigger: {
                trigger: '.garten-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 0.8,
            },
        });
    });

    root.querySelectorAll<HTMLElement>('.story-arch').forEach((arch, index) => {
        gsap.fromTo(
            arch,
            { clipPath: 'inset(100% 0% 0% 0% round 999px 999px 0 0)' },
            {
                clipPath: 'inset(0% 0% 0% 0% round 999px 999px 0 0)',
                duration: 1.1,
                delay: (index % 2) * 0.12,
                ease: 'hereby.inOut',
                scrollTrigger: { trigger: arch, start: 'top 85%' },
            },
        );
    });

    root.querySelectorAll<HTMLElement>('.event-card').forEach((card) => {
        gsap.from(card.querySelector('.event-node'), {
            scale: 0.4,
            rotate: -60,
            opacity: 0,
            duration: 0.7,
            scrollTrigger: { trigger: card, start: 'top 85%' },
        });
        gsap.from(card.querySelectorAll('time, div'), {
            opacity: 0,
            y: 16,
            stagger: 0.08,
            duration: 0.6,
            scrollTrigger: { trigger: card, start: 'top 85%' },
        });
    });
}
