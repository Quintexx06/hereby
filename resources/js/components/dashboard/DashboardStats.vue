<script setup lang="ts">
import { computed } from 'vue';
import { dashboardHero as copy } from '@/content/dashboard';
import type { WeddingOverview } from '@/types';

const props = defineProps<{ overview: WeddingOverview }>();

const stats = computed(() => [
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
    <dl class="stat-strip">
        <div v-for="stat in stats" :key="stat.label" class="stat-tile">
            <dt class="text-sm text-muted-foreground">{{ stat.label }}</dt>
            <dd class="stat-value">{{ stat.value }}</dd>
        </div>
    </dl>
</template>
