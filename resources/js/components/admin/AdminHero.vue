<script setup lang="ts">
import { Sparkles } from '@lucide/vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { adminCopy as copy, adminWelcome } from '@/content/admin';

defineProps<{
    stats: { label: string; value: number }[];
}>();
defineEmits<{ tour: [] }>();
</script>

<!-- Masthead with the roses in its margin, then the numbers set in type. -->
<template>
    <div>
        <PageMasthead :title="copy.title" :lede="copy.lede">
            <template #actions>
                <button
                    type="button"
                    class="text-action hit-area"
                    @click="$emit('tour')"
                >
                    <Sparkles class="size-4" /> {{ adminWelcome.open }}
                </button>
            </template>
        </PageMasthead>

        <dl class="figure-row">
            <div v-for="stat in stats" :key="stat.label" class="stat-figure">
                <dt class="figure-label">{{ stat.label }}</dt>
                <dd class="figure-value">{{ stat.value }}</dd>
            </div>
        </dl>
    </div>
</template>
