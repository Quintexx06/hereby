/** Mirrors App\Actions\Kitchen\BuildKitchenSheet::summary(). No allergy text, by design. */
import type { EventType } from '@/types/wedding';

export type KitchenEvent = {
    id: number;
    type: EventType;
    name: string | null;
    starts_at: string;
    attending: number;
    children: number;
    pending: number;
    menus: { label: string; count: number }[];
};
