import { computed, reactive } from 'vue';
import { VENUE_TIME_ZONE } from '@/lib/format';

export type ShowcaseCouple = { first: string; second: string; date: string };

/** The couple as the example site shows them. */
export type ShowcaseCoupleSite = {
    first: string;
    second: string;
    monogram: string;
    url: string;
    date: string;
};

/** "Zoë Müller" → "zoe-mueller", as a subdomain would spell it. */
export function toSubdomainPart(name: string): string {
    return name
        .toLowerCase()
        .replace(/ä/g, 'ae')
        .replace(/ö/g, 'oe')
        .replace(/ü/g, 'ue')
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

/** "2027-06-19" → "Samstag, 19. Juni 2027"; null for anything else. */
export function formatWeddingDate(isoDay: string): string | null {
    if (!/^\d{4}-\d{2}-\d{2}$/.test(isoDay)) {
        return null;
    }

    const day = new Date(`${isoDay}T12:00:00Z`);
    if (Number.isNaN(day.getTime())) {
        return null;
    }

    return new Intl.DateTimeFormat('de-CH', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        timeZone: VENUE_TIME_ZONE,
    }).format(day);
}

/**
 * The couple a visitor types into the landing studio. Empty fields fall back
 * to the demo couple; nothing is stored or sent anywhere.
 */
export function useShowcaseCouple(fallback: ShowcaseCouple) {
    const couple = reactive<ShowcaseCouple>({
        first: '',
        second: '',
        date: fallback.date,
    });

    const first = computed(() => couple.first.trim() || fallback.first);
    const second = computed(() => couple.second.trim() || fallback.second);

    const site = computed((): ShowcaseCoupleSite => {
        const slug = [first.value, second.value]
            .map(toSubdomainPart)
            .filter(Boolean)
            .join('-und-');

        return {
            first: first.value,
            second: second.value,
            monogram:
                `${first.value.charAt(0)} & ${second.value.charAt(0)}`.toUpperCase(),
            url: `${slug || 'ihr'}.hereby.ch`,
            date:
                formatWeddingDate(couple.date) ??
                formatWeddingDate(fallback.date) ??
                '',
        };
    });

    return { couple, site };
}
