<script setup lang="ts">
import SiteFooter from '@/components/marketing/SiteFooter.vue';
</script>

<!--
    Brand surface: light by default whatever the OS setting; dark sections
    opt in with `.stage` (ADR 0009). The header lives in the hero it floats over.
-->
<template>
    <div
        class="surface-light flex min-h-svh flex-col bg-background text-foreground"
    >
        <main class="flex-1">
            <slot />
        </main>
        <SiteFooter />
    </div>
</template>
