/** Mirrors App\Http\Resources\ReplyFormResource and SaveReplyRequest. */
import type { Wedding, WeddingEvent } from '@/types/wedding';

export type ReplyStatus = 'attending' | 'declined';

export type ReplyGuest = {
    id: number;
    firstName: string;
    lastName: string | null;
    isChild: boolean;
    hasDietaryNotes: boolean;
};

export type ReplyForm = {
    household: { name: string; plusOneAllowed: boolean };
    wedding: Wedding;
    open: boolean;
    links: { invitation: string; reply: string };
    answered: boolean;
    guests: ReplyGuest[];
    plusOne: {
        firstName: string;
        menu: string | null;
        hasDietaryNotes: boolean;
    } | null;
    events: WeddingEvent[];
    answers: {
        guestId: number;
        eventId: number;
        status: ReplyStatus;
        menu: string | null;
    }[];
    questions: {
        menus: { key: string; label: string }[];
        childrenMenu: boolean;
        shuttle: boolean;
        stay: boolean;
        song: boolean;
    };
    extras: {
        shuttleSeats: number | null;
        needsStay: boolean | null;
        songWish: string | null;
    };
};

/** One person's answer for one event while the form is being filled in. */
export type DraftAnswer = { status: ReplyStatus | null; menu: string | null };

/** Allergies are write-only: `notes` is only what the guest types now. */
export type DraftNotes = { notes: string; clear: boolean };
