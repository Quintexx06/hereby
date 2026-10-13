<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import KitchenEventRow from '@/components/kitchen/KitchenEventRow.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { FileSpreadsheet, Printer } from '@lucide/vue';
import { dashboardPage } from '@/content/dashboard';
import { kitchenPage as copy } from '@/content/kitchen';
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
        <PageMasthead :title="copy.title" :lede="copy.lede" scene="flutes" />

        <div class="kitchen-layout">
            <section class="kitchen-card" aria-labelledby="kitchen-parts">
                <h2 id="kitchen-parts" class="kitchen-card-title">
                    {{ copy.parts }}
                </h2>
                <p v-if="events.length === 0" class="text-muted-foreground">
                    {{ copy.empty }}
                </p>
                <ul v-else class="flex flex-col">
                    <KitchenEventRow
                        v-for="event in events"
                        :key="event.id"
                        :event="event"
                    />
                </ul>
            </section>

            <aside class="flex flex-col gap-6">
                <section class="kitchen-card" aria-labelledby="kitchen-exports">
                    <h2 id="kitchen-exports" class="kitchen-card-title">
                        {{ copy.exports }}
                    </h2>
                    <a
                        :href="sheet.url(wedding.id)"
                        target="_blank"
                        rel="noopener"
                        class="kitchen-action"
                    >
                        <Printer
                            class="kitchen-action-icon"
                            aria-hidden="true"
                        />
                        <span>
                            <span class="font-semibold">{{ copy.print }}</span>
                            <span class="block text-sm text-muted-foreground">{{
                                copy.printHint
                            }}</span>
                        </span>
                    </a>
                    <a
                        :href="csv.url(wedding.id)"
                        class="kitchen-action"
                        download
                    >
                        <FileSpreadsheet
                            class="kitchen-action-icon"
                            aria-hidden="true"
                        />
                        <span>
                            <span class="font-semibold">{{ copy.csv }}</span>
                            <span class="block text-sm text-muted-foreground">{{
                                copy.csvHint
                            }}</span>
                        </span>
                    </a>
                </section>

                <section class="kitchen-card" aria-labelledby="kitchen-service">
                    <h2 id="kitchen-service" class="kitchen-card-title">
                        {{ copy.service }}
                    </h2>
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
                            ><span class="font-semibold tabular-nums">{{
                                stays
                            }}</span>
                        </li>
                    </ul>
                    <div v-if="songs.length" class="flex flex-col gap-1">
                        <p class="text-sm font-medium">{{ copy.songs }}</p>
                        <ul class="text-sm text-muted-foreground">
                            <li v-for="song in songs" :key="song">
                                {{ song }}
                            </li>
                        </ul>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</template>
