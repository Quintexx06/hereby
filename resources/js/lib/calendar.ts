import type { WeddingEvent } from '@/types';

/** `20270619T120000Z`: the compact UTC form calendar links expect. */
function utc(iso: string): string {
    return new Date(iso)
        .toISOString()
        .replace(/[-:]/g, '')
        .replace(/\.\d{3}/, '');
}

/**
 * A Google Calendar link spanning the household's day, from the first part
 * to the end of the last. Google takes one entry per link; the .ics file and
 * the subscription carry every part separately.
 */
export function googleCalendarUrl(
    title: string,
    events: WeddingEvent[],
    details: string,
): string | null {
    const first = events[0];
    const last = events.at(-1);

    if (!first || !last) {
        return null;
    }

    const end =
        last.endsAt ??
        new Date(
            new Date(last.startsAt).getTime() + 2 * 3600_000,
        ).toISOString();
    const params = new URLSearchParams({
        action: 'TEMPLATE',
        text: title,
        dates: `${utc(first.startsAt)}/${utc(end)}`,
        details,
        location: [first.locationName, first.address]
            .filter(Boolean)
            .join(', '),
    });

    return `https://calendar.google.com/calendar/render?${params.toString()}`;
}

/** The same .ics URL as a subscription, so later changes reach the guest. */
export function webcalUrl(icsUrl: string): string {
    return icsUrl.replace(/^https?:/, 'webcal:');
}

/** Whole days from today (in the venue's zone) to the wedding date. */
export function daysUntil(isoDate: string, now = new Date()): number {
    const today = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'Europe/Zurich',
    }).format(now);

    return Math.max(
        0,
        Math.round(
            (Date.parse(isoDate.slice(0, 10)) - Date.parse(today)) / 86_400_000,
        ),
    );
}
