<script setup lang="ts">
import { Search } from '@lucide/vue';
import { computed } from 'vue';
import { Input } from '@/components/ui/input';
import { replyStatus } from '@/content/dashboard';
import { guestsPage } from '@/content/guests';
import type { HouseholdRow, ReplyStatusKey } from '@/types';

const props = defineProps<{ households: HouseholdRow[] }>();
const query = defineModel<string>('query', { required: true });
const filter = defineModel<ReplyStatusKey | 'all'>('filter', {
    required: true,
});

const counts = computed(() => {
    const result: Record<string, number> = { all: props.households.length };
    props.households.forEach(
        (household) =>
            (result[household.reply_status] =
                (result[household.reply_status] ?? 0) + 1),
    );

    return result;
});

const filters = [
    { value: 'all' as const, label: guestsPage.all },
    ...(Object.keys(replyStatus) as ReplyStatusKey[]).map((value) => ({
        value,
        label: replyStatus[value].label,
    })),
];
</script>

<template>
    <div
        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
        <div class="relative w-full sm:max-w-xs">
            <Search class="search-icon" aria-hidden="true" />
            <Input
                v-model="query"
                type="search"
                class="h-11 pl-10"
                :placeholder="guestsPage.search"
                :aria-label="guestsPage.search"
            />
        </div>
        <div role="radiogroup" aria-label="Filter" class="flex flex-wrap gap-2">
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
</template>
