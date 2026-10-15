/** Mirrors App\Http\Controllers\Content\ContentBlockController. */
import type { ContentBlockType, EventType, Locale } from '@/types/wedding';

export type BlockText = {
    title: string | null;
    body: string | null;
    items: { question: string; answer: string; url?: string }[];
};

export type EditableBlock = {
    id: number;
    type: ContentBlockType;
    event_id: number | null;
    content: Partial<Record<Locale, BlockText>>;
};

export type ContentWedding = {
    id: number;
    languages: Locale[];
    default_locale: Locale;
    venue: string | null;
    contact: {
        contact_name: string | null;
        contact_email: string | null;
        contact_phone: string | null;
    };
};

/** One of the couple's photos on Inhalte, cover first. */
export type CouplePhoto = { id: number; url: string; focus: [number, number] };

export type ContentEvent = {
    id: number;
    type: EventType;
    name: string | null;
    starts_at: string;
};
