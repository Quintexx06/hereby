import { createInertiaApp } from '@inertiajs/vue3';
import { defineAsyncComponent } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import { registerDirectives } from '@/directives';
import { initializeFlashToast } from '@/lib/flashToast';

/*
 * Layouts load on demand: a guest opening a personal link must not download
 * the couple's app (sidebar, dialogs, auth) to see an invitation (rule 2).
 */
const AppLayout = defineAsyncComponent(() => import('@/layouts/AppLayout.vue'));
const AuthLayout = defineAsyncComponent(
    () => import('@/layouts/AuthLayout.vue'),
);
const MarketingLayout = defineAsyncComponent(
    () => import('@/layouts/MarketingLayout.vue'),
);
const SettingsLayout = defineAsyncComponent(
    () => import('@/layouts/settings/Layout.vue'),
);

const appName = import.meta.env.VITE_APP_NAME || 'Hereby';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome' || name.startsWith('legal/'):
                return MarketingLayout;
            case name.startsWith('invitation/') || name.startsWith('setup/'):
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    withApp: (app) => registerDirectives(app),
    progress: {
        color: 'oklch(0.8 0.09 7)',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
