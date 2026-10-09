/** Mirrors App\Http\Resources\WeddingSetupResource and the setup enums. */
import type {
    EventType,
    Locale,
    LookStyle,
    WeddingTheme,
} from '@/types/wedding';

export type SetupStepKey =
    | 'paar'
    | 'datum'
    | 'ort'
    | 'ablauf'
    | 'gaeste'
    | 'look'
    | 'uebersicht';

export type Celebration = 'day' | 'evening' | 'day_and_evening';

export type GuestEstimate = 'up_to_50' | 'up_to_100' | 'up_to_150' | 'over_150';

export type ProgrammeRow = {
    type: EventType;
    time: string;
    day_offset: number;
    name: string | null;
};

export type SwissAddress = {
    label: string;
    street: string;
    postcode: string;
    town: string;
    lat: number;
    lng: number;
    reference: string;
};

export type WeddingSetup = {
    id: number;
    partner_one: string | null;
    partner_two: string | null;
    couple_names: string;
    wedding_date: string | null;
    rsvp_deadline: string | null;
    venue_name: string | null;
    venue_address: string | null;
    venue_postcode: string | null;
    venue_town: string | null;
    venue_lat: number | null;
    venue_lng: number | null;
    venue_reference: string | null;
    celebration: Celebration | null;
    guest_estimate: GuestEstimate | null;
    languages: Locale[];
    default_locale: Locale;
    theme: WeddingTheme;
    look_styles: LookStyle[];
    look_wishes: string | null;
    events: ProgrammeRow[];
};

/** The single form all steps edit; the live preview reads it too. */
export type SetupForm = Omit<WeddingSetup, 'id' | 'couple_names'> & {
    venue_undecided: boolean;
};

export type SetupStepState = { value: SetupStepKey; reachable: boolean };

export type ThemeSuggestion = { theme: WeddingTheme; reason: string };
