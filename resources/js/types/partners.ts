/** Partner accounts (spec 2026-10-15-partner-accounts). */
export type PartnerType = 'venue' | 'photographer' | 'planner';

export type CalendarWedding = {
    id: number;
    couple_names: string;
    date: string | null;
    deadline: string | null;
    open: boolean;
    households: number;
    answered: number;
    attending: number;
    events: {
        name: string;
        attending: number;
        menus: { label: string; count: number }[];
    }[];
    uploads: boolean;
};

export type VenueEnquiry = {
    id: number;
    name: string;
    email: string;
    preferred_date: string | null;
    message: string | null;
    received: string | null;
};

export type ConnectedPartner = {
    id: number;
    name: string;
    type: PartnerType;
    type_label: string;
    town: string | null;
    since: string | null;
};

export type PartnerInviteRow = {
    id: number;
    type: PartnerType;
    name: string;
    email: string;
};

export type SuggestedPartner = {
    name: string;
    type_label: string;
    town: string | null;
    code: string;
};

export type AdminPartner = {
    id: number;
    type: PartnerType;
    type_label: string;
    name: string;
    code: string;
    login: string | null;
    weddings: number;
    is_showcase: boolean;
    email: string | null;
    phone: string | null;
    website: string | null;
    town: string | null;
    /** Team-only: a personal sentence for the pitch mail. */
    pitch_note: string | null;
    /** Signed, 90 days: the venue's own demo site, for the pitch. */
    demo_url: string | null;
};

/** One distinct warning or error in the team's «Protokoll». */
export type LogEntryRow = {
    id: number;
    level: string;
    is_error: boolean;
    message: string;
    exception: string | null;
    location: string | null;
    url: string | null;
    trace: string | null;
    count: number;
    first_seen: string;
    last_seen: string;
    resolved: boolean;
};
