import {
    Building2,
    CalendarDays,
    CalendarHeart,
    Camera,
    Gem,
    Handshake,
    Presentation,
    UserRound,
    Globe,
    HeartHandshake,
    Images,
    Inbox,
    LayoutGrid,
    LifeBuoy,
    Martini,
    PhoneCall,
    ListChecks,
    NotebookPen,
    Sparkles,
    Users,
    UtensilsCrossed,
} from '@lucide/vue';
import { dashboard, home } from '@/routes';
import { index as adminBriefs } from '@/routes/admin/briefs';
import { index as adminChase } from '@/routes/admin/chase';
import { index as adminInquiries } from '@/routes/admin/inquiries';
import { index as adminPartners } from '@/routes/admin/partners';
import { index as adminWeddings } from '@/routes/admin/weddings';
import { index as content } from '@/routes/weddings/content';
import { edit as details } from '@/routes/weddings/details';
import { show as bespoke } from '@/routes/weddings/bespoke';
import { show as evening } from '@/routes/weddings/evening';
import { show as extras } from '@/routes/weddings/extras';
import { calendar, preview } from '@/routes/partner';
import { edit as partnerProfile } from '@/routes/partner/profile';
import { index as weddingPartners } from '@/routes/weddings/partners';
import { index as guests } from '@/routes/weddings/guests';
import { index as kitchen } from '@/routes/weddings/kitchen';
import { index as memories } from '@/routes/weddings/memories';
import { edit as rsvpSettings } from '@/routes/weddings/rsvp-settings';
import { show as setup } from '@/routes/weddings/setup';
import type { NavItem, SharedPartner, SharedWedding } from '@/types';

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
        items.push({
            title: 'Eckdaten',
            href: details([wedding.id, 'paar']),
            icon: HeartHandshake,
        });
        items.push({
            title: 'Inhalte',
            href: content(wedding.id),
            icon: NotebookPen,
        });
        items.push({
            title: 'Antwortformular',
            href: rsvpSettings(wedding.id),
            icon: ListChecks,
        });
        items.push({
            title: 'Küche & Service',
            href: kitchen(wedding.id),
            icon: UtensilsCrossed,
        });
        items.push({
            title: 'Der Abend',
            href: evening(wedding.id),
            icon: Martini,
        });
        items.push({
            title: 'Erinnerungen',
            href: memories(wedding.id),
            icon: Images,
        });
        items.push({
            title: 'Massanfertigung',
            href: bespoke(wedding.id),
            icon: Gem,
        });
        items.push({
            title: 'Partner',
            href: weddingPartners(wedding.id),
            icon: Handshake,
        });
        items.push({
            title: 'Extras',
            href: extras(wedding.id),
            icon: Sparkles,
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

/**
 * The Hereby team's own section (admins only): every wedding, the questions
 * from the landing page (badged while open) and the public site.
 */
export function adminNavItems(openInquiries: number): NavItem[] {
    return [
        { title: 'Hochzeiten', href: adminWeddings(), icon: CalendarHeart },
        {
            title: 'Anfragen',
            href: adminInquiries(),
            icon: Inbox,
            badge: openInquiries || undefined,
        },
        { title: 'Nachfassen', href: adminChase(), icon: PhoneCall },
        { title: 'Massanfertigung', href: adminBriefs(), icon: Gem },
        { title: 'Partner', href: adminPartners(), icon: Building2 },
        { title: 'Website ansehen', href: home(), icon: Globe },
    ];
}

/**
 * A venue's, photographer's or planner's own navigation: their weddings,
 * their profile, and for Showcase venues the tour preview.
 */
export function partnerNavItems(partner: SharedPartner): NavItem[] {
    const items: NavItem[] = [
        {
            title: 'Hochzeiten',
            href: calendar(),
            icon: partner.type === 'photographer' ? Camera : CalendarDays,
        },
        { title: 'Profil', href: partnerProfile(), icon: UserRound },
    ];

    if (partner.type === 'venue' && partner.is_showcase) {
        items.push({
            title: 'Vorschau für Besichtigungen',
            href: preview(),
            icon: Presentation,
        });
    }

    return items;
}

export const secondaryNavItems: NavItem[] = [
    { title: 'Fragen? Schreibt uns', href: '/#fragen', icon: LifeBuoy },
];
