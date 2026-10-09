<script setup lang="ts">
import { computed } from 'vue';
import { dashboardHero as copy } from '@/content/dashboard';
import type { WeddingOverview } from '@/types';

const props = defineProps<{ overview: WeddingOverview }>();

const countdown = computed(() => {
    const days = props.overview.days_left;

    if (days === null) {
        return [];
    }

    return [
        {
            label: days >= 0 ? copy.countdownLabel(days) : copy.celebrated,
            value: days >= 0 ? days : '♥',
            accent: true,
        },
    ];
});

const stats = computed(() => [
    ...countdown.value,
    { label: copy.stats.households, value: props.overview.households },
    { label: copy.stats.guests, value: props.overview.guests },
    { label: copy.stats.answered, value: props.overview.replies.answered },
    {
        label: copy.stats.waiting,
        value:
            props.overview.replies.opened + props.overview.replies.never_opened,
    },
]);
</script>

<template>
    <dl class="figure-row" :data-count="stats.length">
        <div v-for="stat in stats" :key="stat.label" class="stat-figure">
            <dt class="figure-label">{{ stat.label }}</dt>
            <dd
                class="figure-value"
                :class="'accent' in stat ? 'text-brand' : undefined"
            >
                {{ stat.value }}
            </dd>
        </div>
    </dl>
</template>
