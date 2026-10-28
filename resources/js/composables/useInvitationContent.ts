import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { daysUntil } from '@/lib/calendar';
import {
    formatDate,
    formatDay,
    formatList,
    formatTime,
    venueDay,
    VENUE_TIME_ZONE,
} from '@/lib/format';
import type {
    ContentBlockType,
    Invitation,
    InvitationBlock,
    WeddingEvent,
} from '@/types';

/** One part of the day, ready to set: its words, its time, its way there. */
export type ProgrammeItem = {
    event: WeddingEvent;
    title: string;
    time: string;
    /** «Fr., 18. Juni» when the programme spans several days, else null. */
    day: string | null;
    route: string | null;
};

/**
 * Where the day happens: the first place on the wedding day itself, so a
 * civil ceremony the day before does not stand in the hero.
 */
export function placeOfTheDay(
    events: WeddingEvent[],
    date: string,
): string | null {
    const placed = events.filter((event) => event.locationName);
    const onTheDay = placed.find(
        (event) => venueDay(event.startsAt) === date.slice(0, 10),
    );

    return (onTheDay ?? placed[0])?.locationName ?? null;
}

/** A plain maps link: nothing is requested until the guest taps it. */
export function routeTo(...parts: (string | null)[]): string | null {
    const place = parts.filter(Boolean).join(', ');

    return place
        ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place)}`
        : null;
}

/**
 * What every design sets from one invitation (ADR 0015): the couple's two
 * names, the date in words and in parts, the household's greeting, the
 * day's programme, the couple's chapters by kind and their photos. The
 * designs differ in layout, type and colour, never in what guests learn.
 */
export function useInvitationContent(invitation: () => Invitation) {
    const { t, localeTag } = useTrans();

    const wedding = computed(() => invitation().wedding);

    const names = computed(() => {
        const [first, ...rest] = wedding.value.coupleNames.split(/\s+&\s+/);

        return { first, second: rest.join(' & ') };
    });

    const date = computed(() => {
        const at = new Date(wedding.value.date);
        const part = (options: Intl.DateTimeFormatOptions) =>
            new Intl.DateTimeFormat(localeTag(), {
                ...options,
                timeZone: VENUE_TIME_ZONE,
            }).format(at);

        return {
            long: formatDate(wedding.value.date, localeTag()),
            weekday: part({ weekday: 'long' }),
            day: part({ day: 'numeric' }),
            month: part({ month: 'long' }),
            year: part({ year: 'numeric' }),
            numeric: part({
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
            }),
            daysToGo: daysUntil(wedding.value.date),
        };
    });

    const place = computed(() =>
        placeOfTheDay(invitation().events, wedding.value.date),
    );

    const deadline = computed(() =>
        wedding.value.rsvpDeadline
            ? formatDate(wedding.value.rsvpDeadline, localeTag())
            : null,
    );

    /* «Liebe Sandra und Peter»: the words around the names, the names apart. */
    const greeting = computed(() => {
        const mark = '\u0000';
        const key =
            invitation().guests.length === 1
                ? 'invitation.dear_one'
                : 'invitation.dear';
        const [before, after = ''] = t(key, { names: mark }).split(mark);
        const guests = formatList(
            invitation().guests.map((guest) => guest.firstName),
            localeTag(),
        );

        return { before, names: guests, after };
    });

    const programme = computed<ProgrammeItem[]>(() => {
        const events = invitation().events;
        const severalDays =
            new Set(events.map((event) => venueDay(event.startsAt))).size > 1;

        return events.map((event) => ({
            event,
            title: event.name ?? t(`invitation.event_types.${event.type}`),
            time: formatTime(event.startsAt, localeTag()),
            day: severalDays ? formatDay(event.startsAt, localeTag()) : null,
            route: routeTo(event.locationName, event.address),
        }));
    });

    const block = (type: ContentBlockType): InvitationBlock | null =>
        invitation().blocks.find((each) => each.type === type) ?? null;

    /* Place, travel, stay and dress code, in the couple's order. */
    const practical = computed(() =>
        invitation().blocks.filter((each) =>
            ['venue', 'travel', 'stay', 'dress_code'].includes(each.type),
        ),
    );

    const heading = (each: InvitationBlock): string =>
        each.title ?? t(`invitation.blocks.${each.type}`);

    const photos = computed(() => wedding.value.photos);
    const photoAlt = computed(() =>
        t('invitation.photo_alt', { couple: wedding.value.coupleNames }),
    );

    const canReply = computed(
        () =>
            invitation().rsvpOpen &&
            !invitation().reply.answered &&
            invitation().links !== null,
    );

    return {
        wedding,
        names,
        date,
        place,
        deadline,
        greeting,
        programme,
        story: computed(() => block('story')),
        faq: computed(() => block('faq')),
        practical,
        heading,
        photos,
        photoAlt,
        canReply,
    };
}
