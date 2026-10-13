<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ArrowDown, ArrowUp } from '@lucide/vue';
import { witnessPage as copy } from '@/content/witness';

export type WitnessProgrammeItem = {
    id: number;
    kind: string;
    title: string;
    contact: string;
    notes: string | null;
    minutes: number;
    status: 'pending' | 'accepted' | 'declined';
    updateUrl: string;
};

const props = defineProps<{
    item: WitnessProgrammeItem;
    first: boolean;
    last: boolean;
}>();

defineEmits<{ move: [direction: -1 | 1] }>();

function decide(status: 'accepted' | 'declined'): void {
    router.patch(props.item.updateUrl, { status }, { preserveScroll: true });
}
</script>

<!-- One offer: who, what, how long, and the witnesses' decision. -->
<template>
    <li class="witness-item" :data-status="item.status">
        <div class="flex min-w-0 flex-col gap-1">
            <p class="font-semibold">{{ item.title }}</p>
            <p class="text-sm text-muted-foreground">
                {{ copy.kinds[item.kind] }} · {{ item.contact }} ·
                {{ item.minutes }} Min.
                <template v-if="item.notes"> · {{ item.notes }}</template>
            </p>
            <p class="caption">{{ copy.status[item.status] }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                class="witness-action"
                :disabled="item.status === 'accepted'"
                @click="decide('accepted')"
            >
                {{ copy.accept }}
            </button>
            <button
                type="button"
                class="witness-action"
                :disabled="item.status === 'declined'"
                @click="decide('declined')"
            >
                {{ copy.decline }}
            </button>
            <button
                type="button"
                class="witness-move"
                :aria-label="copy.up"
                :disabled="first"
                @click="$emit('move', -1)"
            >
                <ArrowUp class="size-4" />
            </button>
            <button
                type="button"
                class="witness-move"
                :aria-label="copy.down"
                :disabled="last"
                @click="$emit('move', 1)"
            >
                <ArrowDown class="size-4" />
            </button>
        </div>
    </li>
</template>
