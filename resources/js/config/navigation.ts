import { CalendarHeart, LayoutGrid, LifeBuoy, Users } from '@lucide/vue';
import { dashboard } from '@/routes';
import { index as guests } from '@/routes/weddings/guests';
import { show as setup } from '@/routes/weddings/setup';
import type { NavItem, SharedWedding } from '@/types';

/**
 * Single source of truth for app navigation. Sidebar and header layouts
 * both read from here. Items follow the couple's wedding: "Gäste" appears
 * once the website exists, "Einrichten" while it is still a draft.
 */
export function mainNavItems(wedding: SharedWedding | null): NavItem[] {
    const items: NavItem[] = [
        { title: 'Übersicht', href: dashboard(), icon: LayoutGrid },
    ];

    if (wedding?.status === 'active') {
        items.push({
            title: 'Gäste',
            href: guests(wedding.id),
            icon: Users,
            badge: wedding.households || undefined,
        });
    }

    if (wedding?.status === 'draft') {
        items.push({
            title: 'Einrichten',
            href: setup([wedding.id, wedding.setup_step ?? 'paar']),
            icon: CalendarHeart,
        });
    }

    return items;
}

export const secondaryNavItems: NavItem[] = [
    { title: 'Fragen? Schreibt uns', href: '/#fragen', icon: LifeBuoy },
];
