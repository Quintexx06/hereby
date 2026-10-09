import type { InertiaForm } from '@inertiajs/vue3';
import { inject, provide } from 'vue';
import type { InjectionKey } from 'vue';
import type { SetupForm } from '@/types/setup';

const key: InjectionKey<InertiaForm<SetupForm>> = Symbol('setup-form');

/** The setup page owns one form; every step and the preview share it. */
export function provideSetupForm(form: InertiaForm<SetupForm>): void {
    provide(key, form);
}

export function useSetupForm(): InertiaForm<SetupForm> {
    const form = inject(key);

    if (!form) {
        throw new Error('useSetupForm() must be used inside the setup page.');
    }

    return form;
}
