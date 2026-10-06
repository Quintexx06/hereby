import { BookOpen, FolderGit2, LayoutGrid } from '@lucide/vue';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

/**
 * Single source of truth for app navigation. Sidebar and header layouts
 * both read from here — add new sections in one place.
 */
export const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

export const secondaryNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/quintexx06/hereby',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
