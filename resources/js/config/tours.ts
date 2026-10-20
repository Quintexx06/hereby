import { adminTour } from '@/content/admin';
import type { landingPhotos } from '@/content/landing-photos';
import { coupleTour } from '@/content/tour';
import type { TourCopyStep } from '@/content/tour';
import { index as chase } from '@/routes/admin/chase';
import { index as inquiries } from '@/routes/admin/inquiries';
import { index as log } from '@/routes/admin/log';
import { index as partners } from '@/routes/admin/partners';
import { index as content } from '@/routes/weddings/content';
import { index as guests } from '@/routes/weddings/guests';
import { edit as rsvp } from '@/routes/weddings/rsvp-settings';

/** A tour step ready to show: its copy and where on the page it points. */
export type TourStep = {
    selector: string | null;
    photo?: keyof typeof landingPhotos;
    title: string;
    text: string;
    interactive?: boolean;
};

const mark = (name: string) => `[data-tour="${name}"]`;
const navLink = (url: string) => `.app-nav-item[href="${url}"]`;

function resolve(
    steps: TourCopyStep[],
    selectors: Record<string, string>,
): TourStep[] {
    return steps.map(({ target, ...step }) => ({
        ...step,
        selector: target ? (selectors[target] ?? mark(target)) : null,
    }));
}

/** The team tour: `data-tour` marks on the page, plus the team nav. */
export function adminTourSteps(): TourStep[] {
    return resolve(adminTour, {
        inquiries: navLink(inquiries().url),
        chase: navLink(chase().url),
        partners: navLink(partners().url),
        log: navLink(log().url),
    });
}

/** The couple tour: the overview's marks, plus their wedding's nav. */
export function coupleTourSteps(weddingId: number): TourStep[] {
    return resolve(coupleTour, {
        guests: navLink(guests(weddingId).url),
        content: navLink(content(weddingId).url),
        rsvp: navLink(rsvp(weddingId).url),
        help: '.app-nav-item-quiet',
    });
}

export function findTourTarget(step: TourStep): Element | null {
    return step.selector ? document.querySelector(step.selector) : null;
}
