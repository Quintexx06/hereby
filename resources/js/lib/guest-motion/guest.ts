import type * as Kit from '@/lib/gsap';

type GsapKit = typeof Kit;

/**
 * Shared guest-page choreography, used by every template: headings rise word
 * by word from behind a mask, drawn ornaments trace themselves in, and the
 * closing band's title settles. Each template adds its own signature moves.
 */
export function sharedMotion(
    { gsap, ScrollTrigger, SplitText }: GsapKit,
    root: HTMLElement,
) {
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

    /* The couple's photos open like a curtain, from the top, and settle. */
    root.querySelectorAll<HTMLElement>('.inv-gallery').forEach((gallery) => {
        const frames =
            gallery.querySelectorAll<HTMLElement>('.inv-gallery-frame');
        gsap.timeline({
            scrollTrigger: { trigger: gallery, start: 'top 80%' },
        })
            .fromTo(
                frames,
                { clipPath: 'inset(0% 0% 100% 0%)' },
                {
                    clipPath: 'inset(0% 0% 0% 0%)',
                    duration: 1.1,
                    stagger: 0.14,
                    ease: 'hereby.inOut',
                },
            )
            .from(
                gallery.querySelectorAll('img'),
                {
                    scale: 1.15,
                    duration: 1.6,
                    stagger: 0.14,
                    ease: 'hereby.out',
                },
                '<',
            );
    });

    /* The running band leans with the scroll's speed. */
    root.querySelectorAll<HTMLElement>('.inv-ticker-band').forEach((band) => {
        const skew = gsap.quickTo(band, 'skewX', {
            duration: 0.5,
            ease: 'hereby.out',
        });
        ScrollTrigger.create({
            trigger: band,
            onUpdate: (self) =>
                skew(gsap.utils.clamp(-10, 10, self.getVelocity() / -150)),
        });
    });

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
