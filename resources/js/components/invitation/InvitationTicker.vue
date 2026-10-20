<script setup lang="ts">
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingEvent } from '@/types';

/*
 * The parts of the day running past as one band, as on the landing:
 * solid and outlined italic in turn, a full stop in the accent. It repeats
 * the programme, so screen readers skip it.
 */
const props = defineProps<{ events: WeddingEvent[] }>();
const { t } = useTrans();

const words = computed(() =>
    props.events.map(
        (event) => event.name ?? t(`invitation.event_types.${event.type}`),
    ),
);
</script>

<template>
    <div v-if="words.length > 1" class="inv-ticker" aria-hidden="true">
        <div class="inv-ticker-band">
            <template v-for="copy in 4" :key="copy">
                <span
                    v-for="(word, index) in words"
                    :key="`${copy}-${index}`"
                    class="inv-ticker-word"
                    >{{ word }}<span class="inv-ticker-dot">.</span></span
                >
            </template>
        </div>
    </div>
</template>
