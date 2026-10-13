<script setup lang="ts">
import { previewSeating } from '@/content/locked-previews';
</script>

<!-- The seating plan: tables with who sits where. -->
<template>
    <div class="grid gap-4 sm:grid-cols-3">
        <div
            v-for="table in previewSeating"
            :key="table.table"
            class="locked-preview-tile"
        >
            <p class="font-display text-lg font-bold">{{ table.table }}</p>
            <ul class="grid grid-cols-2 gap-2">
                <li
                    v-for="guest in table.guests"
                    :key="guest"
                    class="flex items-center gap-2 text-sm"
                >
                    <span class="size-6 rounded-full bg-brand/20" />
                    {{ guest }}
                </li>
            </ul>
        </div>
    </div>
</template>
