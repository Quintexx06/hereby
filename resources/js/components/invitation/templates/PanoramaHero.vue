<script setup lang="ts">
import { computed } from 'vue';
import PanoramaBokeh from '@/components/invitation/templates/PanoramaBokeh.vue';
import { useTrans } from '@/composables/useTrans';
import { daysUntil } from '@/lib/calendar';
import { formatDate } from '@/lib/format';
import type { Wedding } from '@/types';

const props = defineProps<{ wedding: Wedding; place: string | null }>();
const { t, tc, localeTag } = useTrans();

const date = computed(() => formatDate(props.wedding.date, localeTag()));
</script>

<!-- Panorama: the names as a running title across the screen, lights behind. -->
<template>
    <header class="panorama-hero">
        <PanoramaBokeh />
        <h1 class="sr-only">
            {{ t('invitation.title', { couple: wedding.coupleNames }) }}
        </h1>
        <div class="panorama-marquee" aria-hidden="true">
            <div class="panorama-track">
                <span v-for="copy in 4" :key="copy" class="panorama-names">
                    {{ wedding.coupleNames }}<span class="panorama-dot">·</span>
                </span>
            </div>
        </div>
        <div class="panorama-meta">
            <p class="panorama-date">{{ date }}</p>
            <div class="flex flex-col gap-1 sm:items-end sm:text-right">
                <p class="text-lg font-medium">{{ t('invitation.marry') }}</p>
                <p v-if="place" class="text-muted-foreground">{{ place }}</p>
                <p class="invitation-countdown self-start sm:self-end">
                    {{ tc('invitation.countdown', daysUntil(wedding.date)) }}
                </p>
            </div>
        </div>
    </header>
</template>
