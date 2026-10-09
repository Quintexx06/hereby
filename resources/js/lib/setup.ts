import { eventTypes, languages } from '@/content/setup';
import type { PreviewHousehold } from '@/content/invitation-preview';
import { formatDate } from '@/lib/format';
import type { SetupForm, SetupStepKey } from '@/types/setup';

/** The fields each step sends; everything else in the form stays local. */
export const stepFields: Record<SetupStepKey, (keyof SetupForm)[]> = {
    paar: ['partner_one', 'partner_two'],
    datum: ['wedding_date', 'rsvp_deadline'],
    ort: [
        'venue_undecided',
        'venue_name',
        'venue_address',
        'venue_postcode',
        'venue_town',
        'venue_lat',
        'venue_lng',
        'venue_reference',
    ],
    ablauf: ['celebration', 'events'],
    gaeste: ['guest_estimate', 'languages', 'default_locale'],
    look: ['theme', 'look_styles', 'look_wishes'],
    uebersicht: [],
};

export function pickStepFields(
    step: SetupStepKey,
    form: SetupForm,
): Partial<SetupForm> {
    return Object.fromEntries(
        stepFields[step].map((field) => [field, form[field]]),
    );
}

/** Chronological: the day before, the day itself, the day after; then by time. */
export function sortProgramme<T extends { day_offset: number; time: string }>(
    rows: T[],
): T[] {
    return [...rows].sort(
        (a, b) => a.day_offset - b.day_offset || a.time.localeCompare(b.time),
    );
}

/** Six weeks before the wedding: enough time to chase the last replies. */
export function suggestedDeadline(weddingDate: string): string {
    const date = new Date(`${weddingDate}T12:00:00`);
    date.setDate(date.getDate() - 42);

    return date.toISOString().slice(0, 10);
}

export function daysUntil(isoDate: string): number {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    return Math.round(
        (new Date(`${isoDate}T00:00:00`).getTime() - today.getTime()) /
            86_400_000,
    );
}

/**
 * The couple's own invitation, as the first household on their list will
 * see it. Placeholders stand in until they answer.
 */
export function previewFromForm(form: SetupForm): PreviewHousehold {
    const one = form.partner_one?.trim() || 'Anna';
    const two = form.partner_two?.trim() || 'Luca';
    const place = form.venue_undecided
        ? ''
        : (form.venue_town ?? form.venue_name ?? '');

    return {
        locale: 'de',
        greeting: 'Für Heidi, Peter und Lina',
        title: `${one} & ${two} heiraten`,
        date: form.wedding_date
            ? formatDate(form.wedding_date, 'de-CH')
            : 'Datum folgt',
        heading: 'Eure Einladung',
        events: sortProgramme(form.events)
            .slice(0, 4)
            .map((event) => ({
                time: event.time,
                name: event.name || eventTypes[event.type],
                place: event.type === 'civil_ceremony' ? '' : place,
            })),
        action: 'Jetzt antworten',
        deadline: form.rsvp_deadline
            ? `Bitte antwortet bis ${formatDate(form.rsvp_deadline, 'de-CH')}`
            : `Auf ${languages[form.default_locale]}`,
    };
}
