<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, ArrowUpRight, Sparkles } from '@lucide/vue';
import { computed } from 'vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { dashboardHero as copy } from '@/content/dashboard';
import { tourCopy } from '@/content/tour';
import { formatDate } from '@/lib/format';
import { preview } from '@/routes/weddings';
import { index as guests } from '@/routes/weddings/guests';
import type { DashboardWedding } from '@/types';

const props = defineProps<{
    wedding: DashboardWedding;
}>();
defineEmits<{ tour: [] }>();

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
    <PageMasthead :title="wedding.couple_names" :lede="subtitle" scene="roses">
        <template #actions>
            <a
                :href="preview.url(wedding.id)"
                target="_blank"
                rel="noopener"
                class="text-action hit-area"
                data-tour="preview"
            >
                {{ copy.preview }} <ArrowUpRight class="size-4" />
            </a>
            <Link :href="guests(wedding.id)" class="text-action hit-area">
                {{ copy.addGuests }} <ArrowRight class="size-4" />
            </Link>
            <button
                type="button"
                class="text-action hit-area"
                @click="$emit('tour')"
            >
                <Sparkles class="size-4" /> {{ tourCopy.open }}
            </button>
        </template>
    </PageMasthead>
</template>
