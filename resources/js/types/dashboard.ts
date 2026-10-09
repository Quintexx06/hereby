/** Mirrors App\Http\Controllers\DashboardController and BuildWeddingOverview. */
import type { SetupStepKey } from '@/types/setup';
import type { EventType, WeddingTheme } from '@/types/wedding';

export type ReplyStatusKey = 'never_opened' | 'opened' | 'answered';

export type DashboardWedding = {
    id: number;
    status: 'draft' | 'active';
    couple_names: string;
    date: string | null;
    rsvp_deadline: string | null;
    venue: string | null;
    theme: WeddingTheme;
    setup_step: SetupStepKey | null;
    setup_position: number | null;
    setup_total: number;
};

export type OverviewEvent = {
    id: number;
    type: EventType;
    name: string | null;
    starts_at: string;
    invited: number;
    attending: number;
};

export type WeddingOverview = {
    days_left: number | null;
    deadline_days: number | null;
    households: number;
    guests: number;
    replies: Record<ReplyStatusKey, number>;
    events: OverviewEvent[];
    actions: { key: string; count?: number }[];
};
