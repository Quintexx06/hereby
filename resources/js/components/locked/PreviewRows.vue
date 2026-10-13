<script setup lang="ts">
import { previewRows } from '@/content/locked-previews';

defineProps<{ kind: keyof typeof previewRows }>();
</script>

<!-- A ledger of demo rows: acts, group photos or the night's connections. -->
<template>
    <ul class="border-b border-rule">
        <li
            v-for="row in previewRows[kind]"
            :key="row.title"
            class="flex items-center justify-between gap-4 border-t border-rule py-5"
        >
            <span class="flex flex-col gap-1">
                <span class="font-display text-lg font-bold">
                    {{ row.title }}
                </span>
                <span class="caption">{{ row.meta }}</span>
            </span>
            <span class="locked-preview-chip">{{ row.status }}</span>
        </li>
    </ul>
</template>
