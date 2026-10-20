import type * as Kit from '@/lib/gsap';
import { sharedMotion } from '@/lib/guest-motion/guest';

/**
 * Panorama: the running title leans with scroll speed, the day pins and
 * travels sideways on wide screens, and film frames drift at two speeds.
 */
export function panoramaMotion(kit: typeof Kit, root: HTMLElement) {
    const { gsap, ScrollTrigger } = kit;
    sharedMotion(kit, root);

    const track = root.querySelector<HTMLElement>('.panorama-track');
    if (track) {
        const skew = gsap.quickTo(track, 'skewX', {
            duration: 0.5,
            ease: 'hereby.out',
        });
        ScrollTrigger.create({
            trigger: '.panorama-hero',
            start: 'top top',
            end: 'bottom top',
            onUpdate: (self) =>
                skew(gsap.utils.clamp(-12, 12, self.getVelocity() / -120)),
        });
        gsap.to(track, {
            xPercent: -12,
            ease: 'none',
            scrollTrigger: {
                trigger: '.panorama-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true,
            },
        });
    }

    const timeline = root.querySelector<HTMLElement>('.event-timeline');
    const section = timeline?.closest<HTMLElement>('.inv-section');
    /* Wide screens show up to four parts side by side: nothing to travel. */
    if (
        timeline &&
        section &&
        window.matchMedia('(min-width: 768px)').matches &&
        timeline.scrollWidth > timeline.clientWidth + 1
    ) {
        /* Native sideways swipe until here; now the row travels as the page scrolls. */
        timeline.classList.add('is-pinned');
        const distance = () =>
            Math.max(0, timeline.scrollWidth - section.clientWidth);
        gsap.to(timeline, {
            x: () => -distance(),
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top top+=8%',
                end: () => `+=${distance()}`,
                pin: true,
                // The page is a flex column, where pinSpacing would default to off.
                pinSpacing: true,
                scrub: 0.6,
                invalidateOnRefresh: true,
            },
        });
    }

    root.querySelectorAll<HTMLElement>('.story-frame').forEach(
        (frame, index) => {
            gsap.from(frame, {
                y: index % 2 ? 60 : 20,
                opacity: 0,
                duration: 0.9,
                scrollTrigger: { trigger: frame, start: 'top 90%' },
            });
        },
    );
}
