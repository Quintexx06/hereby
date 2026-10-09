<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, ArrowUpRight } from '@lucide/vue';
import { computed } from 'vue';
import { dashboardHero as copy } from '@/content/dashboard';
import { formatDate } from '@/lib/format';
import { preview } from '@/routes/weddings';
import { index as guests } from '@/routes/weddings/guests';
import type { DashboardWedding } from '@/types';

const props = defineProps<{
    wedding: DashboardWedding;
    daysLeft: number | null;
}>();

const subtitle = computed(() =>
    [
        props.wedding.date ? formatDate(props.wedding.date, 'de-CH') : null,
        props.wedding.venue,
    ]
        .filter(Boolean)
        .join(' · '),
);
</script>

<!-- The masthead: their names set large, the date, the days to go. -->
<template>
    <header class="masthead">
        <div>
            <h1 class="masthead-title">{{ wedding.couple_names }}</h1>
            <p class="masthead-meta">{{ subtitle }}</p>
            <p class="masthead-actions">
                <a
                    :href="preview.url(wedding.id)"
                    target="_blank"
                    rel="noopener"
                    class="text-action hit-area"
                >
                    {{ copy.preview }} <ArrowUpRight class="size-4" />
                </a>
                <Link :href="guests(wedding.id)" class="text-action hit-area">
                    {{ copy.addGuests }} <ArrowRight class="size-4" />
                </Link>
            </p>
        </div>

        <p v-if="daysLeft !== null" class="masthead-aside">
            <span class="figure-value text-7xl text-brand">{{
                daysLeft >= 0 ? daysLeft : '♥'
            }}</span>
            <span class="figure-label">{{
                daysLeft >= 0 ? copy.countdownLabel(daysLeft) : copy.celebrated
            }}</span>
        </p>
    </header>
</template>
