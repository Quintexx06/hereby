<script setup lang="ts">
defineProps<{ number: number; title: string; hint?: string }>();
</script>

<!-- One step of the reply-form setup: a numbered stop on a vertical line. -->
<template>
    <li class="rsvp-step">
        <span class="rsvp-step-number" aria-hidden="true">{{ number }}</span>
        <div class="flex min-w-0 flex-col gap-5">
            <div class="flex flex-col gap-1">
                <h2 class="rsvp-step-title">{{ title }}</h2>
                <p v-if="hint" class="field-hint max-w-prose">{{ hint }}</p>
            </div>
            <slot />
        </div>
    </li>
</template>
