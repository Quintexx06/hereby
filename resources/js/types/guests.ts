/** Mirrors GuestsController and the import preview. */
import type { ReplyStatusKey } from '@/types/dashboard';
import type { Locale } from '@/types/wedding';

export type HouseholdRow = {
    id: number;
    name: string;
    email: string | null;
    locale: Locale;
    plus_one_allowed: boolean;
    reply_status: ReplyStatusKey;
    link: string;
    guests: { id: number; name: string; is_child: boolean }[];
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
