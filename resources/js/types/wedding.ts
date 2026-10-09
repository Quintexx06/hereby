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
    blocks: InvitationBlock[];
    rsvpOpen: boolean;
    links: { invitation: string; reply: string; calendar: string } | null;
    reply: { answered: boolean; attending: number; invited: number };
};

export type ContentBlockType =
    | 'story'
    | 'venue'
    | 'dress_code'
    | 'faq'
    | 'travel'
    | 'stay';

/** One content block as a guest sees it (App\Actions\Invitations\VisibleBlocks). */
export type InvitationBlock = {
    id: number;
    type: ContentBlockType;
    title: string | null;
    body: string | null;
    items: { question: string; answer: string; url?: string }[];
    venue: {
        name: string | null;
        address: string | null;
        route: string | null;
    } | null;
};
