<script setup lang="ts">
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { formatDate, formatList } from '@/lib/format';
import type { Guest, Wedding } from '@/types';

const props = defineProps<{
    wedding: Wedding;
    guests: Guest[];
}>();

const { t, localeTag } = useTrans();

const guestNames = computed(() =>
    formatList(
        props.guests.map((guest) => guest.firstName),
        localeTag(),
    ),
);
</script>

<template>
    <header class="flex flex-col gap-6 pb-12 text-center">
        <p class="eyebrow">{{ t('invitation.for') }} {{ guestNames }}</p>
        <h1 class="display-lg">
            {{ t('invitation.title', { couple: wedding.coupleNames }) }}
        </h1>
        <p class="font-display text-xl italic">
            {{ formatDate(wedding.date, localeTag()) }}
        </p>
    </header>
</template>
