import type { Directive } from 'vue';
import type { Auth } from '@/types/auth';
import type { Translations } from '@/types/i18n';
import type {
    AccountSummary,
    SharedPartner,
    SharedWedding,
} from '@/types/navigation';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            locale: string;
            translations: Translations;
            currentWedding: SharedWedding | null;
            account: AccountSummary | null;
            auth: Auth;
            /** Open landing questions; null unless the user is an admin. */
            adminInbox: number | null;
            /** Which view an admin works in; null unless the user is an admin. */
            adminView: 'team' | 'couple' | null;
            /** Open errors in the log; null unless the user is an admin. */
            adminIssues: number | null;
            /** A venue, photographer or planner account; null for couples. */
            partner: SharedPartner | null;
            sidebarOpen: boolean;
            [key: string]: unknown;
        };
    }
}

declare module 'vue' {
    interface GlobalDirectives {
        vFocus: Directive<HTMLElement, boolean | undefined>;
        vReveal: Directive<HTMLElement, number | undefined>;
    }

    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}
