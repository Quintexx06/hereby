<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Megaphone } from '@lucide/vue';
import { ref } from 'vue';
import { witnessPage as copy } from '@/content/witness';

export type WitnessGroup = {
    id: number;
    title: string;
    place: string | null;
    households: string[];
    calledAt: string | null;
    callUrl: string;
};

defineProps<{ groups: WitnessGroup[] }>();

/* A call mails whole households, so it asks once before it goes out. */
const asking = ref<number | null>(null);

function call(group: WitnessGroup): void {
    asking.value = null;
    router.post(group.callUrl, {}, { preserveScroll: true });
}

const time = (iso: string): string =>
    new Date(iso).toLocaleTimeString('de-CH', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Europe/Zurich',
    });
</script>

<!--
    On the day: call each group photo, after one «sure?»; its guests get a
    mail and a banner.
-->
<template>
    <section class="flex flex-col gap-4">
        <div>
            <h2 class="title">{{ copy.groups }}</h2>
            <p class="caption">{{ copy.groupsHint }}</p>
        </div>
        <p v-if="!groups.length" class="text-muted-foreground">
            {{ copy.groupsEmpty }}
        </p>
        <ol v-else class="flex flex-col">
            <li v-for="group in groups" :key="group.id" class="witness-item">
                <div class="flex min-w-0 flex-col gap-1">
                    <p class="font-semibold">{{ group.title }}</p>
                    <p class="text-sm text-muted-foreground">
                        {{ group.households.join(', ') || '–' }}
                        <template v-if="group.place">
                            · {{ group.place }}</template
                        >
                    </p>
                    <p v-if="group.calledAt" class="caption">
                        {{ copy.calledAt(time(group.calledAt)) }}
                    </p>
                </div>
                <div
                    v-if="asking === group.id"
                    class="confirm-row"
                    role="group"
                    :aria-label="copy.callConfirm(group.title)"
                >
                    <span>{{ copy.callConfirm(group.title) }}</span>
                    <button
                        type="button"
                        class="witness-action gap-2"
                        @click="call(group)"
                    >
                        <Megaphone class="size-4" /> {{ copy.callYes }}
                    </button>
                    <button
                        type="button"
                        class="pill-outline"
                        @click="asking = null"
                    >
                        {{ copy.cancel }}
                    </button>
                </div>
                <button
                    v-else
                    type="button"
                    class="witness-action gap-2"
                    @click="asking = group.id"
                >
                    <Megaphone class="size-4" />
                    {{ group.calledAt ? copy.callAgain : copy.call }}
                </button>
            </li>
        </ol>
    </section>
</template>
