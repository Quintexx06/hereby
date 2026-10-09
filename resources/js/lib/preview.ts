import { eventTypes } from '@/content/setup';
import type { PreviewHousehold } from '@/content/invitation-preview';
import { formatDate, formatTime } from '@/lib/format';
import type { DashboardWedding, OverviewEvent } from '@/types';

/**
 * The couple's own invitation at phone scale, built from what the dashboard
 * already knows. Addressed to no one in particular: these are not real guests.
 */
export function previewFromWedding(
    wedding: DashboardWedding,
    events: OverviewEvent[],
): PreviewHousehold {
    return {
        locale: 'de',
        greeting: 'Für eure Gäste',
        title: `${wedding.couple_names} heiraten`,
        date: wedding.date ? formatDate(wedding.date, 'de-CH') : 'Datum folgt',
        heading: 'Eure Einladung',
        events: events.slice(0, 4).map((event) => ({
            time: formatTime(event.starts_at, 'de-CH'),
            name: event.name || eventTypes[event.type],
            place: '',
        })),
        action: 'Jetzt antworten',
        deadline: wedding.rsvp_deadline
            ? `Bitte antwortet bis ${formatDate(wedding.rsvp_deadline, 'de-CH')}`
            : '',
    };
}
