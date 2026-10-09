/** Mirrors App\Http\Controllers\Admin\WeddingsController::index. */
import type { SetupStepKey } from '@/types/setup';
import type { LookStyle } from '@/types/wedding';

export type AdminWedding = {
    id: number;
    couple_names: string;
    email: string;
    status: 'draft' | 'active';
    setup_step: SetupStepKey | null;
    date: string | null;
    households: number;
    answered: number;
    look_styles: LookStyle[];
    look_wishes: string | null;
    created_at: string | null;
};

/** Mirrors App\Http\Controllers\Admin\InquiriesController::index. */
export type AdminInquiry = {
    id: number;
    email: string;
    question: string;
    answered: boolean;
    created_at: string | null;
};
