import {
    BedDouble,
    Coffee,
    Gem,
    Landmark,
    MapPin,
    Music,
    Shirt,
    Sparkles,
    TrainFront,
    UtensilsCrossed,
    Wine,
} from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import type { ContentBlockType, EventType } from '@/types';

/** The sign for each part of the day, where a design sets icons. */
export const eventIcons: Record<EventType, LucideIcon> = {
    civil_ceremony: Landmark,
    ceremony: Gem,
    reception: Wine,
    dinner: UtensilsCrossed,
    party: Music,
    brunch: Coffee,
    other: Sparkles,
};

/** The sign for each practical chapter: place, travel, stay, dress code. */
export const blockIcons: Partial<Record<ContentBlockType, LucideIcon>> = {
    venue: MapPin,
    travel: TrainFront,
    stay: BedDouble,
    dress_code: Shirt,
};
