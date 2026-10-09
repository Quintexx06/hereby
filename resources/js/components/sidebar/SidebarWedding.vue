<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { active, sidebarWedding } from '@/content/dashboard';
import { steps } from '@/content/setup';
import { formatDate } from '@/lib/format';
import { daysUntil } from '@/lib/setup';
import type { SetupStepKey } from '@/types';

const page = usePage();
const wedding = computed(() => page.props.currentWedding);

const details = computed(() => {
    const current = wedding.value;

    if (!current) {
        return null;
    }

    if (current.status === 'draft') {
        return sidebarWedding.inSetup(
            steps[(current.setup_step ?? 'paar') as SetupStepKey].label,
        );
    }

    return current.date ? formatDate(current.date, 'de-CH') : null;
});

const countdown = computed(() =>
    wedding.value?.status === 'active' && wedding.value.date
        ? active.daysLeft(daysUntil(wedding.value.date))
        : null,
);
</script>

<!-- Whose wedding this is: the couple's names, the date and the countdown. -->
<template>
    <div
        v-if="wedding"
        class="flex flex-col gap-1 px-4 pt-2 pb-6 group-data-[collapsible=icon]:hidden"
    >
        <p
            class="font-display text-2xl leading-tight font-semibold tracking-[-0.03em] text-balance"
        >
            {{ wedding.couple_names || sidebarWedding.fallbackName }}
        </p>
        <p v-if="details" class="text-sm text-muted-foreground">
            {{ details }}
        </p>
        <p v-if="countdown" class="text-sm font-medium text-brand">
            {{ countdown }}
        </p>
    </div>
</template>
