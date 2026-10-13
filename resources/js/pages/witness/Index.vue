<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Printer } from '@lucide/vue';
import { computed } from 'vue';
import WitnessGroups from '@/components/witness/WitnessGroups.vue';
import type { WitnessGroup } from '@/components/witness/WitnessGroups.vue';
import WitnessItem from '@/components/witness/WitnessItem.vue';
import type { WitnessProgrammeItem } from '@/components/witness/WitnessItem.vue';
import { witnessPage as copy } from '@/content/witness';

const props = defineProps<{
    wedding: {
        coupleNames: string;
        date: string | null;
        budget: number | null;
    };
    programme: WitnessProgrammeItem[];
    groups: WitnessGroup[];
    links: { reorder: string; print: string };
}>();

const minutes = computed(() =>
    props.programme
        .filter((item) => item.status === 'accepted')
        .reduce((sum, item) => sum + item.minutes, 0),
);
const over = computed(
    () => props.wedding.budget !== null && minutes.value > props.wedding.budget,
);

function move(index: number, direction: -1 | 1): void {
    const ids = props.programme.map((item) => item.id);
    [ids[index], ids[index + direction]] = [ids[index + direction], ids[index]];
    router.put(props.links.reorder, { ids }, { preserveScroll: true });
}
</script>

<!--
    The witnesses' private page: no account, one link from the couple. The
    evening's offers to decide and order; the couple only sees counts.
-->
<template>
    <Head :title="copy.title" />

    <main class="witness-page">
        <header class="flex flex-col gap-3">
            <p class="caption">{{ wedding.coupleNames }}</p>
            <h1 class="headline">{{ copy.title }}</h1>
            <p class="lede">{{ copy.lede(wedding.coupleNames) }}</p>
            <p class="publish-note">{{ copy.private }}</p>
        </header>

        <section class="flex flex-col gap-5">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="title">{{ copy.programme }}</h2>
                    <p class="caption" :class="{ 'text-destructive': over }">
                        {{ copy.total(minutes, wedding.budget) }}
                        <template v-if="over"> · {{ copy.over }}</template>
                    </p>
                </div>
                <a
                    :href="links.print"
                    target="_blank"
                    class="text-action hit-area"
                >
                    <Printer class="size-4" /> {{ copy.print }}
                </a>
            </div>

            <p v-if="!programme.length" class="text-muted-foreground">
                {{ copy.empty }}
            </p>
            <ol v-else class="flex flex-col">
                <WitnessItem
                    v-for="(item, index) in programme"
                    :key="item.id"
                    :item="item"
                    :first="index === 0"
                    :last="index === programme.length - 1"
                    @move="move(index, $event)"
                />
            </ol>
        </section>

        <WitnessGroups :groups="groups" />
    </main>
</template>
