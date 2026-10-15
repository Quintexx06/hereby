<script setup lang="ts">
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{ couple: ShowcaseCoupleSite }>();

const footer = studio.site.footer;
</script>

<!-- The footer every couple's site ends with: who to ask, credit, privacy. -->
<template>
    <footer class="sc-footer">
        <p class="sc-footer-couple">
            {{ couple.first }} <span class="text-brand">&amp;</span>
            {{ couple.second }}
        </p>
        <address class="flex flex-col items-center gap-1 text-sm not-italic">
            <span>{{ footer.lead }}</span>
            <span class="font-semibold">{{ footer.name }}</span>
            <span class="sc-footer-link">{{ footer.email }}</span>
        </address>
        <p class="caption">
            {{ footer.credit }} ·
            <span class="sc-footer-link">{{ footer.privacy }}</span>
        </p>
    </footer>
</template>
