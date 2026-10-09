import { useForm } from '@inertiajs/vue3';
import type { InertiaForm } from '@inertiajs/vue3';
import { computed, inject, provide } from 'vue';
import type { ComputedRef, InjectionKey } from 'vue';
import type {
    DraftAnswer,
    DraftNotes,
    ReplyForm,
    ReplyGuest,
    WeddingEvent,
} from '@/types';

type ReplyState = {
    answers: Record<string, DraftAnswer>;
    notes: Record<number, DraftNotes>;
    plusOne: {
        enabled: boolean;
        firstName: string;
        menu: string | null;
    } & DraftNotes;
    shuttleSeats: number;
    needsStay: boolean | null;
    songWish: string;
};

export type ReplyFormApi = {
    form: InertiaForm<ReplyState>;
    reply: ReplyForm;
    answer: (guestId: number, eventId: number) => DraftAnswer;
    menusFor: (guest: ReplyGuest | null) => { key: string; label: string }[];
    asksMenu: (guest: ReplyGuest | null, event: WeddingEvent) => boolean;
    attendingGuests: ComputedRef<ReplyGuest[]>;
    plusOneAtDinner: ComputedRef<boolean>;
    missing: ComputedRef<number>;
    submit: () => void;
};

const key: InjectionKey<ReplyFormApi> = Symbol('reply-form');
const pair = (guestId: number, eventId: number) => `${guestId}:${eventId}`;
const isDinner = (event: WeddingEvent) => event.type === 'dinner';

/**
 * The reply form's state and its rules about what applies. The page creates
 * it once; every block reads it. Mirrors SaveReplyRequest on the server.
 */
export function provideReplyForm(
    reply: ReplyForm,
    childrenLabel: string,
): ReplyFormApi {
    const answers: Record<string, DraftAnswer> = {};

    reply.guests.forEach((guest) =>
        reply.events.forEach((event) => {
            const saved = reply.answers.find(
                (item) =>
                    item.guestId === guest.id && item.eventId === event.id,
            );
            answers[pair(guest.id, event.id)] = {
                status: saved?.status ?? null,
                menu: saved?.menu ?? null,
            };
        }),
    );

    const form = useForm<ReplyState>({
        answers,
        notes: Object.fromEntries(
            reply.guests.map((guest) => [
                guest.id,
                { notes: '', clear: false },
            ]),
        ),
        plusOne: {
            enabled: reply.plusOne !== null,
            firstName: reply.plusOne?.firstName ?? '',
            menu: reply.plusOne?.menu ?? null,
            notes: '',
            clear: false,
        },
        shuttleSeats: reply.extras.shuttleSeats ?? 0,
        needsStay: reply.extras.needsStay,
        songWish: reply.extras.songWish ?? '',
    });

    const answer = (guestId: number, eventId: number) =>
        form.answers[pair(guestId, eventId)];

    const menusFor = (guest: ReplyGuest | null) => [
        ...(guest?.isChild && reply.questions.childrenMenu
            ? [{ key: 'children', label: childrenLabel }]
            : []),
        ...reply.questions.menus,
    ];

    const asksMenu = (guest: ReplyGuest | null, event: WeddingEvent) =>
        isDinner(event) && menusFor(guest).length > 0;

    const attendingGuests = computed(() =>
        reply.guests.filter((guest) =>
            reply.events.some(
                (event) => answer(guest.id, event.id).status === 'attending',
            ),
        ),
    );

    const plusOneAtDinner = computed(
        () =>
            form.plusOne.enabled &&
            reply.events.some(
                (event) =>
                    isDinner(event) &&
                    reply.guests.some(
                        (guest) =>
                            answer(guest.id, event.id).status === 'attending',
                    ),
            ) &&
            reply.questions.menus.length > 0,
    );

    const missing = computed(() => {
        let count = 0;

        reply.guests.forEach((guest) =>
            reply.events.forEach((event) => {
                const current = answer(guest.id, event.id);
                count += current.status === null ? 1 : 0;
                count +=
                    current.status === 'attending' &&
                    asksMenu(guest, event) &&
                    !current.menu
                        ? 1
                        : 0;
            }),
        );

        if (form.plusOne.enabled) {
            count += form.plusOne.firstName.trim() ? 0 : 1;
            count += plusOneAtDinner.value && !form.plusOne.menu ? 1 : 0;
        }

        return count;
    });

    function submit(): void {
        form.transform((data) => ({
            answers: Object.entries(data.answers).map(([id, item]) => {
                const [guestId, eventId] = id.split(':').map(Number);

                return {
                    guest_id: guestId,
                    event_id: eventId,
                    status: item.status,
                    menu: item.status === 'attending' ? item.menu : null,
                };
            }),
            guests: Object.entries(data.notes).map(([id, item]) => ({
                id: Number(id),
                dietary_notes: item.notes.trim() || null,
                clear_dietary: item.clear,
            })),
            plus_one: data.plusOne.enabled
                ? {
                      first_name: data.plusOne.firstName.trim(),
                      menu: data.plusOne.menu,
                      dietary_notes: data.plusOne.notes.trim() || null,
                      clear_dietary: data.plusOne.clear,
                  }
                : null,
            shuttle_seats: reply.questions.shuttle ? data.shuttleSeats : null,
            needs_stay: reply.questions.stay ? data.needsStay : null,
            song_wish: reply.questions.song
                ? data.songWish.trim() || null
                : null,
        })).put(reply.links.reply, { preserveScroll: true });
    }

    const api: ReplyFormApi = {
        form,
        reply,
        answer,
        menusFor,
        asksMenu,
        attendingGuests,
        plusOneAtDinner,
        missing,
        submit,
    };
    provide(key, api);

    return api;
}

export function useReplyForm(): ReplyFormApi {
    const api = inject(key);

    if (!api) {
        throw new Error('useReplyForm() must be used inside the reply page.');
    }

    return api;
}
