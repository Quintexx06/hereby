<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import LogEntryCard from '@/components/admin/LogEntryCard.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { adminLog as copy } from '@/content/log';
import { destroy, index } from '@/routes/admin/log';
import type { LogEntryRow } from '@/types';

type Filter = keyof typeof copy.filters;

defineProps<{
    show: Filter;
    counts: Record<Filter, number>;
    entries: LogEntryRow[];
}>();

defineOptions({
    layout: { breadcrumbs: [{ title: 'Protokoll', href: index() }] },
});

const filters = Object.keys(copy.filters) as Filter[];
</script>

<!-- The team's log: what went wrong in the running app, and what is handled. -->
<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <PageMasthead :title="copy.title" :lede="copy.lede">
            <template v-if="show === 'erledigt' && counts.erledigt" #actions>
                <button
                    type="button"
                    class="text-action"
                    @click="
                        router.delete(destroy.url(), { preserveScroll: true })
                    "
                >
                    {{ copy.clear }}
                </button>
            </template>
        </PageMasthead>

        <nav class="chip-row" aria-label="Filter">
            <Link
                v-for="filter in filters"
                :key="filter"
                :href="
                    index({
                        query: filter === 'offen' ? {} : { zeigen: filter },
                    })
                "
                class="chip"
                :aria-current="show === filter ? 'page' : undefined"
                preserve-scroll
            >
                {{ copy.filters[filter] }}
                <span class="chip-count">{{ counts[filter] }}</span>
            </Link>
        </nav>

        <p v-if="!entries.length" class="admin-empty">{{ copy.empty[show] }}</p>
        <ul v-else class="flex flex-col">
            <LogEntryCard
                v-for="entry in entries"
                :key="entry.id"
                :entry="entry"
            />
        </ul>
    </div>
</template>
