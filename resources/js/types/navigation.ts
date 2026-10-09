import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from '@lucide/vue';

export type BreadcrumbItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
};

export type NavItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    /** A count shown at the end of the row (e.g. households). */
    badge?: number;
};

/** The signed-in couple's current wedding, shared on every page. */
export type SharedWedding = {
    id: number;
    status: 'draft' | 'active';
    couple_names: string;
    date: string | null;
    households: number;
    setup_step: string | null;
};
