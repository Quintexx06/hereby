import type * as Kit from '@/lib/gsap';

type GsapKit = typeof Kit;

/**
 * Shared guest-page choreography, used by every template: headings rise word
 * by word from behind a mask, drawn ornaments trace themselves in, and the
 * closing band's title settles. Each template adds its own signature moves.
 */
export function sharedMotion({ gsap, SplitText }: GsapKit, root: HTMLElement) {
    root.classList.add('gsap-on');

    root.querySelectorAll<HTMLElement>('.inv-heading').forEach((heading) => {
        const split = SplitText.create(heading, {
            type: 'words',
            mask: 'words',
        });
        gsap.from(split.words, {
            yPercent: 110,
            duration: 0.9,
            stagger: 0.06,
            ease: 'hereby.out',
            scrollTrigger: { trigger: heading, start: 'top 85%' },
        });
    });

    root.querySelectorAll<SVGPathElement>('.invitation-ornament path').forEach(
        (path) => {
            gsap.from(path, {
                drawSVG: '50% 50%',
                duration: 1.2,
                ease: 'hereby.inOut',
                scrollTrigger: { trigger: path, start: 'top 90%' },
            });
        },
    );

    const closing = root.querySelector<HTMLElement>('.inv-closing-title');
    if (closing) {
        const chars = SplitText.create(closing, { type: 'chars' }).chars;
        gsap.from(chars, {
            opacity: 0,
            yPercent: 40,
            rotate: 6,
            stagger: 0.03,
            duration: 0.8,
            scrollTrigger: { trigger: closing, start: 'top 80%' },
        });
    }
}

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
