/** Mirrors App\Http\Resources\* — keep in sync with the PHP resources. */

export type Locale = 'de_CH' | 'fr' | 'it' | 'en';

export type WeddingTheme = 'ivory' | 'rose' | 'alpine' | 'riviera' | 'lavanda';

/** Mirrors App\Enums\WeddingTemplate: layout and motion of a guest site. */
export type WeddingTemplate = 'atelier' | 'garten' | 'panorama';

/** Mirrors App\Enums\LookStyle: style directions that brief the team. */
export type LookStyle =
    | 'classic'
    | 'modern'
    | 'romantic'
    | 'natural'
    | 'boho'
    | 'rustic'
    | 'elegant'
    | 'mediterranean'
    | 'vintage'
    | 'minimal'
    | 'playful'
    | 'mountain';

export type EventType =
    | 'civil_ceremony'
    | 'ceremony'
    | 'reception'
    | 'dinner'
    | 'party'
    | 'brunch'
    | 'other';

/** One of the couple's own photos, cover first (WeddingResource). */
export type WeddingPhoto = {
    url: string;
    width: number;
    height: number;
    /** The spot that must stay in view when cropped: [x, y] in percent. */
    focus: [number, number];
};

/** Built-in parts of a guest site a couple may leave out (App\\Enums\\SiteSection). */
export type SiteSection = 'opening' | 'calendar' | 'ornaments' | 'closing';

export type Wedding = {
    coupleNames: string;
    date: string;
    rsvpDeadline: string | null;
    theme: WeddingTheme;
    accentColor: string | null;
    hiddenSections: SiteSection[];
    template: WeddingTemplate;
    photos: WeddingPhoto[];
    contact: {
        name: string | null;
        email: string | null;
        phone: string | null;
    } | null;
    privacyUrl: string;
    venueCredit: { name: string; url: string } | null;
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

/** The banner of a venue's sales demo or a public sample site. */
export type InvitationDemo = {
    /** The venue a demo is for; null on a sample site. */
    venue: string | null;
    template: WeddingTemplate;
    /** Where the banner's last link goes: the venue page or sign-up. */
    more: string;
    sample?: boolean;
    /** A sample site's own address per template. */
    links?: Partial<Record<WeddingTemplate, string>>;
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
    evening: Evening | null;
};

/** One of the household's tables (App\Actions\Evening\BuildEvening). */
export type EveningTable = {
    name: string;
    yours: string[];
    mates: { name: string; note: string | null }[];
};

/** «Der Abend» on the personal link; null without Atelier. */
export type Evening = {
    tables: EveningTable[];
    wayHome: { label: string; place: string | null; departsAt: string }[];
    call: { title: string; place: string | null } | null;
    offerUrl: string | null;
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
