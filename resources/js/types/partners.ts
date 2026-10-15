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
    sees_numbers: boolean;
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
};
