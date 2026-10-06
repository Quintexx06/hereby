/**
 * Locale-aware formatting. Always pass the BCP 47 tag from useTrans().
 * Times are shown in the venue's zone, never the guest's: a guest in New York
 * must still read "16:00" for a 16:00 ceremony in Lucerne.
 */
export const VENUE_TIME_ZONE = 'Europe/Zurich';

export function formatDate(iso: string, localeTag: string): string {
    return new Intl.DateTimeFormat(localeTag, {
        dateStyle: 'long',
        timeZone: VENUE_TIME_ZONE,
    }).format(new Date(iso));
}

export function formatTime(iso: string, localeTag: string): string {
    return new Intl.DateTimeFormat(localeTag, {
        timeStyle: 'short',
        timeZone: VENUE_TIME_ZONE,
    }).format(new Date(iso));
}

/** Short day for event lists, e.g. "Fr., 18. Juni". */
export function formatDay(iso: string, localeTag: string): string {
    return new Intl.DateTimeFormat(localeTag, {
        weekday: 'short',
        day: 'numeric',
        month: 'long',
        timeZone: VENUE_TIME_ZONE,
    }).format(new Date(iso));
}

/** "Anna, Luca and Mia" in the guest's language. */
export function formatList(items: string[], localeTag: string): string {
    return new Intl.ListFormat(localeTag, {
        style: 'long',
        type: 'conjunction',
    }).format(items);
}
