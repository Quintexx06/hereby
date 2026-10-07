<script setup lang="ts">
import type { PreviewHousehold } from '@/content/invitation-preview';

defineProps<{
    household: PreviewHousehold;
}>();
</script>

<!-- A guest's personal invitation, drawn at phone scale (illustration only). -->
<template>
    <div :lang="household.locale" class="phone-screen">
        <p class="text-[0.8125rem] text-muted-foreground">
            {{ household.greeting }}
        </p>
        <p
            class="mt-3 font-display text-[1.75rem] leading-[1.02] font-semibold tracking-[-0.035em]"
        >
            {{ household.title }}
        </p>
        <p class="mt-2 text-sm font-medium text-brand tabular-nums">
            {{ household.date }}
        </p>

        <p class="mt-7 text-xs font-semibold text-muted-foreground">
            {{ household.heading }}
        </p>
        <ol class="mt-2">
            <li
                v-for="event in household.events"
                :key="event.time + event.name"
                class="grid grid-cols-[3.25rem_1fr] border-t border-rule py-2.5 text-sm"
            >
                <span class="font-semibold text-brand tabular-nums">{{
                    event.time
                }}</span>
                <span>
                    <span class="block font-semibold">{{ event.name }}</span>
                    <span class="block text-xs text-muted-foreground">{{
                        event.place
                    }}</span>
                </span>
            </li>
        </ol>

        <div class="mt-auto pt-6">
            <span class="phone-action">{{ household.action }}</span>
            <p class="mt-3 text-center text-xs text-muted-foreground">
                {{ household.deadline }}
            </p>
        </div>
    </div>
</template>
