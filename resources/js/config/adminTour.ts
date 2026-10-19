import type { adminWelcome } from '@/content/admin';
import { index as chase } from '@/routes/admin/chase';
import { index as inquiries } from '@/routes/admin/inquiries';
import { index as log } from '@/routes/admin/log';
import { index as partners } from '@/routes/admin/partners';

export type TourStep = (typeof adminWelcome.steps)[number];
type Target = NonNullable<TourStep['target']>;

const navLink = (url: string) => `.app-nav-item[href="${url}"]`;

/** Where each step of the team tour points: `data-tour` marks or nav links. */
const selectors: Record<Target, () => string> = {
    create: () => '[data-tour="create"]',
    stats: () => '[data-tour="stats"]',
    checklist: () => '[data-tour="checklist"]',
    view: () => '[data-tour="view"]',
    weddings: () => '[data-tour="weddings"]',
    inquiries: () => navLink(inquiries().url),
    chase: () => navLink(chase().url),
    partners: () => navLink(partners().url),
    log: () => navLink(log().url),
};

export function findTourTarget(step: TourStep): Element | null {
    return step.target
        ? document.querySelector(selectors[step.target]())
        : null;
}
