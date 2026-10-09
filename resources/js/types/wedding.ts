/** Mirrors App\Http\Resources\* — keep in sync with the PHP resources. */

export type Locale = 'de_CH' | 'fr' | 'it' | 'en';

export type WeddingTheme = 'ivory' | 'rose' | 'alpine' | 'riviera' | 'lavanda';

export type EventType =
    | 'civil_ceremony'
    | 'ceremony'
    | 'reception'
    | 'dinner'
    | 'party'
    | 'brunch'
    | 'other';

export type Wedding = {
    coupleNames: string;
    date: string;
    rsvpDeadline: string | null;
    theme: WeddingTheme;
};

export type WeddingEvent = {
    id: number;
    type: EventType;
    name: string | null;
    startsAt: string;
    endsAt: string | null;
    locationName: string | null;
    address: string | null;
};

export type Guest = {
    id: number;
    firstName: string;
    lastName: string | null;
    isChild: boolean;
};

export type Invitation = {
    household: { name: string; plusOneAllowed: boolean };
    wedding: Wedding;
    guests: Guest[];
    events: WeddingEvent[];
    rsvpOpen: boolean;
    links: { invitation: string; reply: string };
    reply: { answered: boolean; attending: number; invited: number };
};
