<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import { computed } from 'vue';
import { publishBanner as copy } from '@/content/publish';
import { show as publish } from '@/routes/weddings/publish';

/* Shown while the couple's wedding is set up but its links are closed. */
const page = usePage();
const wedding = computed(() => page.props.currentWedding);
const visible = computed(
    () => wedding.value?.status === 'active' && !wedding.value.published,
);
</script>

<template>
    <aside v-if="visible && wedding" class="publish-banner">
        <div class="flex flex-col gap-1">
            <p class="font-semibold">{{ copy.title }}</p>
            <p class="text-sm text-muted-foreground">{{ copy.text }}</p>
        </div>
        <Link :href="publish(wedding.id)" class="text-action hit-area shrink-0">
            {{ copy.action }} <ArrowRight class="size-4" />
        </Link>
    </aside>
</template>
