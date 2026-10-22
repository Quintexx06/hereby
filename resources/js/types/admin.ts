/** Mirrors App\Http\Controllers\Admin\WeddingsController::index. */
import type { PlanKey } from '@/types/billing';
import type { SetupStepKey } from '@/types/setup';
import type { WeddingTemplate } from '@/types/wedding';

export type AdminWedding = {
    id: number;
    couple_names: string;
    email: string;
    status: 'draft' | 'active';
    plan: PlanKey | null;
    published: boolean;
    domain: string | null;
    domain_connected: boolean;
    setup_step: SetupStepKey | null;
    date: string | null;
    households: number;
    answered: number;
    template: WeddingTemplate;
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
