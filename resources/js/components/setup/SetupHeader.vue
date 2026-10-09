<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import HerebyWordmark from '@/components/brand/HerebyWordmark.vue';
import { actions } from '@/content/setup';
import { dashboard } from '@/routes';
</script>

<template>
    <header class="setup-header">
        <Link :href="dashboard()" aria-label="Zum Dashboard"
            ><HerebyWordmark
        /></Link>
        <Link
            :href="dashboard()"
            class="link-underline text-sm text-muted-foreground"
        >
            <span class="sm:hidden">{{ actions.saveAndExitShort }}</span>
            <span class="max-sm:hidden">{{ actions.saveAndExit }}</span>
        </Link>
    </header>
</template>
