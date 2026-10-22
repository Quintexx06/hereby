<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';

defineProps<{
    household: string;
    names: { first: string; second: string };
    date: { weekday: string; long: string };
    place: string | null;
}>();
const { t } = useTrans();
</script>

<!--
    Salon opens like an engraved card laid on a dark table, addressed to the
    household: the personal link is the envelope. A double hairline frame,
    the names in titling capitals with a Caslon italic ampersand, the date
    in italic, the place in spaced capitals. Type alone, no photo.
-->
<template>
    <header class="salon-hero">
        <div class="salon-card">
            <p class="salon-for">{{ t('invitation.for') }} {{ household }}</p>
            <h1 class="salon-names">
                <span>{{ names.first }}</span>
                <template v-if="names.second">
                    <span class="salon-amp">&amp;</span>
                    <span>{{ names.second }}</span>
                </template>
                <span class="sr-only">{{ t('invitation.marry') }}</span>
            </h1>
            <p class="salon-date">{{ date.weekday }}, {{ date.long }}</p>
            <p v-if="place" class="salon-place">{{ place }}</p>
        </div>
    </header>
</template>
