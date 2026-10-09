/** Mirrors GuestsController and the import preview. */
import type { ReplyStatusKey } from '@/types/dashboard';
import type { EventType, Locale } from '@/types/wedding';

export type HouseholdRow = {
    id: number;
    name: string;
    email: string | null;
    locale: Locale;
    plus_one_allowed: boolean;
    reply_status: ReplyStatusKey;
    link: string;
    event_ids: number[];
    guests: {
        id: number;
        name: string;
        first_name: string;
        last_name: string | null;
        is_child: boolean;
    }[];
};

/** A person as edited in a form; `id` is set for people already saved. */
export type EditableGuest = {
    id?: number;
    first_name: string;
    last_name: string | null;
    is_child: boolean;
};

export type GuestEvent = {
    id: number;
    type: EventType;
    name: string | null;
    starts_at: string;
};

export type ImportGuest = {
    first_name: string;
    last_name: string | null;
    is_child: boolean;
    duplicate?: boolean;
};

export type ImportHousehold = {
    name: string;
    email: string | null;
    locale: Locale | null;
    plus_one_allowed?: boolean;
    guests: ImportGuest[];
};

export type GuestsWedding = {
    id: number;
    couple_names: string;
    default_locale: Locale;
    languages: Locale[];
};
