<script setup lang="ts">
import { ArrowDown } from '@lucide/vue';
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { daysUntil } from '@/lib/calendar';
import { formatDate } from '@/lib/format';
import type { Wedding } from '@/types';

const props = defineProps<{
    wedding: Wedding;
    place: string | null;
}>();

const { t, tc, localeTag } = useTrans();

/* "Anna & Luca" set as two lines, the ampersand in the accent voice. */
const names = computed(() => {
    const [first, ...rest] = props.wedding.coupleNames.split(/\s+&\s+/);

    return { first, second: rest.join(' & ') };
});
const date = computed(() => formatDate(props.wedding.date, localeTag()));
const days = computed(() => daysUntil(props.wedding.date));
const seal = computed(() =>
    `${t('invitation.save_the_date')} · ${date.value} · `.repeat(2),
);
</script>

<!-- The first screen: their names as large as the landing's, the date, the place. -->
<template>
    <header class="inv-hero">
        <div class="inv-hero-glow" aria-hidden="true" />

        <h1 class="inv-names">
            <span class="inv-name">{{ names.first }}</span>
            <template v-if="names.second">
                <span class="inv-amp accent">&amp;</span>
                <span class="inv-name">{{ names.second }}</span>
            </template>
            <span class="sr-only">{{ t('invitation.marry') }}</span>
        </h1>

        <div class="inv-hero-meta">
            <p class="text-lg font-medium" aria-hidden="true">
                {{ t('invitation.marry') }}
            </p>
            <p class="inv-hero-date">{{ date }}</p>
            <p v-if="place" class="text-muted-foreground">{{ place }}</p>
            <p class="invitation-countdown">
                {{ tc('invitation.countdown', days) }}
            </p>
        </div>

        <svg class="inv-seal" viewBox="0 0 200 200" aria-hidden="true">
            <defs>
                <path
                    id="seal-path"
                    d="M100 100m-82 0a82 82 0 1 1 164 0a82 82 0 1 1-164 0"
                />
            </defs>
            <text>
                <textPath href="#seal-path">{{ seal }}</textPath>
            </text>
        </svg>

        <a href="#inv-letter" class="inv-scroll">
            <ArrowDown class="size-4" aria-hidden="true" />
            {{ t('invitation.scroll') }}
        </a>
    </header>
</template>
