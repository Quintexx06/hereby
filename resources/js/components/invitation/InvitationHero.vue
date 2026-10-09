<script setup lang="ts">
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { daysUntil } from '@/lib/calendar';
import { formatDate, formatList } from '@/lib/format';
import type { Guest, Wedding } from '@/types';

const props = defineProps<{
    wedding: Wedding;
    guests: Guest[];
    place: string | null;
}>();

const { t, tc, localeTag } = useTrans();

const guestNames = computed(() =>
    formatList(
        props.guests.map((guest) => guest.firstName),
        localeTag(),
    ),
);

/* "Anna & Luca" set as three lines, the ampersand in the theme's accent. */
const names = computed(() => {
    const [first, ...rest] = props.wedding.coupleNames.split(/\s+&\s+/);

    return { first, second: rest.join(' & ') };
});
const days = computed(() => daysUntil(props.wedding.date));
const seal = computed(() =>
    `${t('invitation.save_the_date')} · ${formatDate(props.wedding.date, localeTag())} · `.repeat(
        2,
    ),
);
</script>

<template>
    <header class="invitation-hero">
        <p class="caption">{{ t('invitation.for') }} {{ guestNames }}</p>

        <h1 class="invitation-names">
            <span>{{ names.first }}</span>
            <template v-if="names.second">
                <span class="invitation-amp">&amp;</span>
                <span>{{ names.second }}</span>
            </template>
        </h1>

        <div class="grid place-items-center">
            <svg
                class="invitation-seal"
                viewBox="0 0 200 200"
                aria-hidden="true"
            >
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
            <p class="invitation-date">
                <span class="text-sm font-medium text-muted-foreground">{{
                    t('invitation.marry')
                }}</span>
                <span class="text-2xl font-semibold text-brand tabular-nums">{{
                    formatDate(wedding.date, localeTag())
                }}</span>
                <span v-if="place" class="text-sm">{{ place }}</span>
            </p>
        </div>

        <p class="invitation-countdown">
            {{ tc('invitation.countdown', days) }}
        </p>
    </header>
</template>
