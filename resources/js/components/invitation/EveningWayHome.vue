<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { formatTime } from '@/lib/format';
import type { Evening } from '@/types';

defineProps<{ connections: Evening['wayHome'] }>();

const { t, localeTag } = useTrans();

/* A countdown in the last six hours before each departure. */
const COUNTDOWN_MINUTES = 6 * 60;
const now = ref(Date.now());
let timer: ReturnType<typeof setInterval> | undefined;
onMounted(() => (timer = setInterval(() => (now.value = Date.now()), 30_000)));
onBeforeUnmount(() => clearInterval(timer));

function status(departsAt: string): string | null {
    const minutes = Math.ceil((Date.parse(departsAt) - now.value) / 60_000);

    if (minutes <= 0) {
        return t('invitation.evening.gone');
    }

    return minutes <= COUNTDOWN_MINUTES
        ? t('invitation.evening.in', { minutes })
        : null;
}
</script>

<!-- «Euer Heimweg»: the night's last connections, counting down on the day. -->
<template>
    <section class="inv-section">
        <h2 class="inv-heading">
            {{ t('invitation.evening.way_home_heading') }}
        </h2>
        <ul class="evening-card evening-mates">
            <li v-for="connection in connections" :key="connection.departsAt">
                <span class="font-semibold text-brand tabular-nums">{{
                    formatTime(connection.departsAt, localeTag())
                }}</span>
                <span class="flex flex-col">
                    <span class="font-semibold">{{ connection.label }}</span>
                    <span
                        v-if="connection.place"
                        class="text-sm text-muted-foreground"
                        >{{
                            t('invitation.evening.from', {
                                place: connection.place,
                            })
                        }}</span
                    >
                </span>
                <span
                    v-if="status(connection.departsAt)"
                    class="evening-countdown"
                    >{{ status(connection.departsAt) }}</span
                >
            </li>
        </ul>
    </section>
</template>
