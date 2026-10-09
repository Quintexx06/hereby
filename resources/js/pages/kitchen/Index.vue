<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { FileSpreadsheet, Printer } from '@lucide/vue';
import { dashboardPage } from '@/content/dashboard';
import { kitchenPage as copy } from '@/content/kitchen';
import { eventTypes } from '@/content/setup';
import { formatDay, formatTime } from '@/lib/format';
import { dashboard } from '@/routes';
import { csv, sheet } from '@/routes/weddings/kitchen';
import type { KitchenEvent } from '@/types';

defineProps<{
    wedding: { id: number; couple_names: string };
    events: KitchenEvent[];
    allergies: number;
    shuttle: number;
    stays: number;
    songs: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <header class="masthead">
            <div class="max-w-2xl">
                <h1 class="masthead-title">{{ copy.title }}</h1>
                <p class="masthead-meta">{{ copy.lede }}</p>
            </div>
        </header>

        <div class="grid gap-3 sm:grid-cols-2">
            <a
                :href="sheet.url(wedding.id)"
                target="_blank"
                rel="noopener"
                class="kitchen-action"
            >
                <Printer class="size-5" aria-hidden="true" />
                <span>
                    <span class="font-semibold">{{ copy.print }}</span>
                    <span class="block text-sm text-muted-foreground">{{
                        copy.printHint
                    }}</span>
                </span>
            </a>
            <a :href="csv.url(wedding.id)" class="kitchen-action" download>
                <FileSpreadsheet class="size-5" aria-hidden="true" />
                <span>
                    <span class="font-semibold">{{ copy.csv }}</span>
                    <span class="block text-sm text-muted-foreground">{{
                        copy.csvHint
                    }}</span>
                </span>
            </a>
        </div>

        <p v-if="events.length === 0" class="text-muted-foreground">
            {{ copy.empty }}
        </p>

        <section
            v-for="event in events"
            :key="event.id"
            class="kitchen-event"
            :aria-labelledby="`kitchen-${event.id}`"
        >
            <header class="flex flex-col gap-1">
                <h2 :id="`kitchen-${event.id}`" class="app-section-title">
                    {{ event.name || eventTypes[event.type] }}
                </h2>
                <p class="text-sm text-muted-foreground">
                    {{ formatDay(event.starts_at, 'de-CH') }},
                    {{ formatTime(event.starts_at, 'de-CH') }}
                </p>
            </header>
            <p class="kitchen-number">{{ copy.attending(event.attending) }}</p>
            <p class="text-sm text-muted-foreground">
                {{ copy.children(event.children)
                }}<template v-if="event.pending"
                    >, {{ copy.pending(event.pending) }}</template
                >
            </p>
            <ul v-if="event.menus.length" class="kitchen-menus">
                <li v-for="menu in event.menus" :key="menu.label">
                    <span>{{ menu.label }}</span>
                    <span class="font-semibold tabular-nums">{{
                        menu.count
                    }}</span>
                </li>
            </ul>
        </section>

        <section class="kitchen-event">
            <h2 class="app-section-title">{{ copy.service }}</h2>
            <p class="text-sm">{{ copy.allergies(allergies) }}</p>
            <ul class="kitchen-menus">
                <li>
                    <span>{{ copy.shuttle }}</span
                    ><span class="font-semibold tabular-nums">{{
                        shuttle
                    }}</span>
                </li>
                <li>
                    <span>{{ copy.stays }}</span
                    ><span class="font-semibold tabular-nums">{{ stays }}</span>
                </li>
            </ul>
            <div v-if="songs.length" class="flex flex-col gap-1">
                <p class="text-sm font-medium">{{ copy.songs }}</p>
                <ul class="text-sm text-muted-foreground">
                    <li v-for="song in songs" :key="song">{{ song }}</li>
                </ul>
            </div>
        </section>
    </div>
</template>
