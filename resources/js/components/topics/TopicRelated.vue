<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import { topics as copy } from '@/content/landing';
import { show } from '@/routes/topics';
import type { RelatedTopic } from '@/types/landing';

defineProps<{ items: RelatedTopic[] }>();
</script>

<!-- Where to read on: three neighbouring topics, linked by name. -->
<template>
    <section
        class="page-container pb-24 sm:pb-32"
        aria-labelledby="topic-related"
    >
        <h2 id="topic-related" class="title mb-8">{{ copy.related }}</h2>
        <ul class="topic-related">
            <li v-for="item in items" :key="item.slug">
                <Link :href="show(item.slug)" class="topic-card group">
                    <span class="text-lg font-semibold">{{ item.label }}</span>
                    <span class="text-sm text-muted-foreground">{{
                        item.description
                    }}</span>
                    <ArrowRight
                        class="ledger-arrow mt-auto"
                        aria-hidden="true"
                    />
                </Link>
            </li>
        </ul>
    </section>
</template>
