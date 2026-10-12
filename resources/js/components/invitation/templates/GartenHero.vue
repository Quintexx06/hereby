<script setup lang="ts">
import { computed } from 'vue';
import GartenBranch from '@/components/invitation/templates/GartenBranch.vue';
import { useTrans } from '@/composables/useTrans';
import { daysUntil } from '@/lib/calendar';
import { formatDate } from '@/lib/format';
import type { Wedding } from '@/types';

const props = defineProps<{ wedding: Wedding; place: string | null }>();
const { t, tc, localeTag } = useTrans();

const names = computed(() => {
    const [first, ...rest] = props.wedding.coupleNames.split(/\s+&\s+/);

    return { first, second: rest.join(' & ') };
});
</script>

<!-- Garten: the names inside a tall arch, branches growing round it, petals falling. -->
<template>
    <header class="garten-hero">
        <div class="garten-petals" aria-hidden="true">
            <span v-for="petal in 9" :key="petal" :style="{ '--p': petal }" />
        </div>
        <GartenBranch side="left" />
        <div class="garten-arch">
            <p class="text-sm font-medium text-muted-foreground">
                {{ formatDate(wedding.date, localeTag()) }}
            </p>
            <h1 class="garten-names">
                <span>{{ names.first }}</span>
                <span v-if="names.second" class="accent text-[0.55em]"
                    >&amp;</span
                >
                <span v-if="names.second">{{ names.second }}</span>
                <span class="sr-only">{{ t('invitation.marry') }}</span>
            </h1>
            <p class="text-lg font-medium" aria-hidden="true">
                {{ t('invitation.marry') }}
            </p>
            <p v-if="place" class="text-muted-foreground">{{ place }}</p>
            <p class="invitation-countdown">
                {{ tc('invitation.countdown', daysUntil(wedding.date)) }}
            </p>
        </div>
        <GartenBranch side="right" />
    </header>
</template>
