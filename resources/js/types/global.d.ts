import type { Directive } from 'vue';
import type { Auth } from '@/types/auth';
import type { Translations } from '@/types/i18n';
import type { SharedWedding, AccountSummary } from '@/types/navigation';

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
