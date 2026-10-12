/** Mirrors App\Http\Controllers\Billing\PublishController::show. */
export type PlanKey = 'signature' | 'atelier' | 'couture';

export type PlanOffer = {
    key: PlanKey;
    /** Full price in CHF. */
    price: number;
    /** What buying it costs now in CHF (the difference on an upgrade). */
    due: number;
    owned: boolean;
    online: boolean;
};

export type PublishWedding = {
    id: number;
    couple_names: string;
    plan: PlanKey | null;
    published: boolean;
    active: boolean;
};
