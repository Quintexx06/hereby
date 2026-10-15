/** «Massanfertigung»: the couple's brief (App\Http\Controllers\Bespoke). */
export type BriefStatus = 'draft' | 'submitted' | 'in_progress' | 'ready';

export type Brief = {
    status: BriefStatus;
    status_label: string;
    wishes: string | null;
    colors: string[];
    mood: string | null;
    links: string | null;
    ready_by: string | null;
    submitted_at: string | null;
    team_note: string | null;
    photos: { id: number; url: string }[];
};

export type BespokeWedding = {
    id: number;
    couple_names: string;
    owned: boolean;
    buyable: boolean;
    price: number;
};

export type AdminBrief = Omit<Brief, 'photos' | 'status_label'> & {
    id: number;
    wedding_id: number;
    couple_names: string;
    date: string | null;
    photos: string[];
};
