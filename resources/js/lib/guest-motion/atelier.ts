import type * as Kit from '@/lib/gsap';
import { sharedMotion } from '@/lib/guest-motion/guest';

type GsapKit = typeof Kit;

/** Atelier: names drift apart on the way out, times roll, the path scrubs. */
export function atelierMotion(kit: GsapKit, root: HTMLElement) {
    const { gsap, SplitText } = kit;
    sharedMotion(kit, root);

    const names = root.querySelector<HTMLElement>('.inv-names');
    if (names) {
        const chars = SplitText.create(names.querySelectorAll('.inv-name'), {
            type: 'chars',
        }).chars;
        gsap.to(chars, {
            yPercent: (index) => -30 - (index % 4) * 18,
            rotate: (index) => (index % 2 ? 1 : -1) * (4 + (index % 3) * 3),
            opacity: 0.15,
            ease: 'none',
            scrollTrigger: {
                trigger: names,
                start: 'top top+=10%',
                end: 'bottom top',
                scrub: 0.6,
            },
        });
    }

    root.querySelectorAll<HTMLElement>('.event-card').forEach((card) => {
        const time = card.querySelector('.event-time');
        const timeline = gsap.timeline({
            scrollTrigger: { trigger: card, start: 'top 85%' },
        });
        timeline.from(card, { opacity: 0, x: -24, duration: 0.6 });
        if (time) {
            const digits = SplitText.create(time, {
                type: 'chars',
                mask: 'chars',
            }).chars;
            timeline.from(
                digits,
                { yPercent: 100, stagger: 0.05, duration: 0.5 },
                '<0.1',
            );
        }
    });

    atelierActs(kit, root);

    const ink = root.querySelector<SVGPathElement>('.story-line-ink');
    if (ink) {
        gsap.fromTo(
            ink,
            { drawSVG: '0%' },
            {
                drawSVG: '100%',
                ease: 'none',
                scrollTrigger: {
                    trigger: '.story-path',
                    start: 'top 70%',
                    end: 'bottom 60%',
                    scrub: true,
                },
            },
        );
    }

    root.querySelectorAll<HTMLElement>('.story-card').forEach((card) => {
        gsap.from(card.children, {
            opacity: 0,
            y: 28,
            stagger: 0.08,
            duration: 0.7,
            scrollTrigger: { trigger: card, start: 'top 80%' },
        });
    });
}

/**
 * Atelier's acts: on wide screens the row pins and travels sideways as the
 * page scrolls, each photo's numeral sliding against it; on phones each act
 * rises as it comes in.
 */
function atelierActs({ gsap }: GsapKit, root: HTMLElement) {
    const track = root.querySelector<HTMLElement>('.inv-acts-track');
    const section = track?.closest<HTMLElement>('.inv-acts');
    if (!track || !section) {
        return;
    }

    const acts = track.querySelectorAll<HTMLElement>('.inv-act');
    const wide = window.matchMedia('(min-width: 1024px)').matches;

    if (wide && track.scrollWidth > track.clientWidth + 1) {
        track.classList.add('is-pinned');
        const distance = () =>
            Math.max(0, track.scrollWidth - section.clientWidth);
        const travel = gsap.to(track, {
            x: () => -distance(),
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top top',
                end: () => `+=${distance()}`,
                pin: true,
                // The page is a flex column, where pinSpacing would default to off.
                pinSpacing: true,
                scrub: 0.6,
                invalidateOnRefresh: true,
            },
        });
        acts.forEach((act) => {
            gsap.from(act.querySelector('.inv-act-numeral'), {
                xPercent: -60,
                opacity: 0,
                ease: 'none',
                scrollTrigger: {
                    trigger: act,
                    containerAnimation: travel,
                    start: 'left 90%',
                    end: 'left 40%',
                    scrub: true,
                },
            });
        });

        return;
    }

    acts.forEach((act) => {
        gsap.from(act.children, {
            opacity: 0,
            y: 40,
            stagger: 0.12,
            duration: 0.9,
            ease: 'hereby.out',
            scrollTrigger: { trigger: act, start: 'top 85%' },
        });
    });
}
