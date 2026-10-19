<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Heart, ShieldCheck } from '@lucide/vue';
import { view as switchView } from '@/routes/admin';

defineProps<{ view: 'team' | 'couple' }>();

const options = [
    { value: 'team', label: 'Team', icon: ShieldCheck },
    { value: 'couple', label: 'Paar', icon: Heart },
] as const;

function choose(value: 'team' | 'couple'): void {
    router.post(switchView.url(), { view: value });
}
</script>

<!--
    Admins only: work as the team (every wedding, inquiries, partners, log)
    or as a couple on their own wedding. A pill glides under the choice.
-->
<template>
    <div
        class="view-switch"
        data-tour="view"
        role="radiogroup"
        aria-label="Ansicht"
        :data-view="view"
    >
        <span class="view-switch-pill" aria-hidden="true" />
        <button
            v-for="option in options"
            :key="option.value"
            type="button"
            role="radio"
            class="view-switch-option"
            :aria-checked="view === option.value"
            :title="`${option.label}-Ansicht`"
            @click="view !== option.value && choose(option.value)"
        >
            <component
                :is="option.icon"
                class="size-4 shrink-0"
                aria-hidden="true"
            />
            <span class="app-nav-label">{{ option.label }}</span>
        </button>
    </div>
</template>
