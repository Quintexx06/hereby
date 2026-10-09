<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Search, UserPlus } from '@lucide/vue';
import { computed, ref } from 'vue';
import HouseholdItem from '@/components/guests/HouseholdItem.vue';
import ImportPanel from '@/components/guests/import/ImportPanel.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { active, replyStatus } from '@/content/dashboard';
import { guestsPage } from '@/content/guests';
import { dashboard } from '@/routes';
import type { GuestsWedding, HouseholdRow, ReplyStatusKey } from '@/types';

const props = defineProps<{
    wedding: GuestsWedding;
    households: HouseholdRow[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Übersicht', href: dashboard() },
            { title: 'Gäste', href: '' },
        ],
    },
});

const query = ref('');
const filter = ref<ReplyStatusKey | 'all'>('all');
const adding = ref(props.households.length === 0);

const guestCount = computed(() =>
    props.households.reduce(
        (sum, household) => sum + household.guests.length,
        0,
    ),
);

const counts = computed(() => {
    const result: Record<string, number> = { all: props.households.length };
    props.households.forEach(
        (household) =>
            (result[household.reply_status] =
                (result[household.reply_status] ?? 0) + 1),
    );

    return result;
});

/* Search matches the household or any person in it, accents ignored. */
const normalise = (value: string) =>
    value
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .toLowerCase();

const visible = computed(() => {
    const needle = normalise(query.value.trim());

    return props.households.filter(
        (household) =>
            (filter.value === 'all' ||
                household.reply_status === filter.value) &&
            (!needle ||
                normalise(
                    [
                        household.name,
                        ...household.guests.map((guest) => guest.name),
                    ].join(' '),
                ).includes(needle)),
    );
});

const filters = computed(() => [
    { value: 'all' as const, label: guestsPage.all },
    ...(Object.keys(replyStatus) as ReplyStatusKey[]).map((value) => ({
        value,
        label: replyStatus[value].label,
    })),
]);
</script>

<template>
    <Head :title="guestsPage.title" />

    <div class="app-page gap-10">
        <header class="flex flex-wrap items-end justify-between gap-6">
            <div class="flex flex-col gap-2">
                <h1 class="app-title">
                    {{
                        households.length
                            ? guestsPage.title
                            : guestsPage.emptyTitle
                    }}
                </h1>
                <p class="text-muted-foreground">
                    {{
                        households.length
                            ? active.counts(households.length, guestCount)
                            : guestsPage.emptyLede
                    }}
                </p>
            </div>
            <Button
                v-if="households.length"
                size="pill"
                :variant="adding ? 'ghost' : 'default'"
                @click="adding = !adding"
            >
                <UserPlus class="size-4" />
                {{ guestsPage.add }}
            </Button>
        </header>

        <ImportPanel v-if="adding" :wedding="wedding" @done="adding = false" />

        <section v-if="households.length" class="flex flex-col gap-5">
            <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="relative w-full sm:max-w-xs">
                    <Search
                        class="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-muted-foreground"
                        aria-hidden="true"
                    />
                    <Input
                        v-model="query"
                        type="search"
                        class="h-11 pl-10"
                        :placeholder="guestsPage.search"
                        :aria-label="guestsPage.search"
                    />
                </div>
                <div
                    role="radiogroup"
                    aria-label="Filter"
                    class="flex flex-wrap gap-2"
                >
                    <label
                        v-for="item in filters"
                        :key="item.value"
                        class="chip h-9 px-4"
                    >
                        <input
                            v-model="filter"
                            type="radio"
                            name="reply_filter"
                            :value="item.value"
                            class="sr-only"
                        />
                        {{ item.label }}
                        <span class="ml-1.5 tabular-nums opacity-60">{{
                            counts[item.value] ?? 0
                        }}</span>
                    </label>
                </div>
            </div>

            <ul class="border-b">
                <HouseholdItem
                    v-for="household in visible"
                    :key="household.id"
                    :household="household"
                />
            </ul>
            <p v-if="visible.length === 0" class="text-muted-foreground">
                {{ guestsPage.noMatches }}
            </p>
        </section>
    </div>
</template>
