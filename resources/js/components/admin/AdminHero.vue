<script setup lang="ts">
import { Plus, Sparkles } from '@lucide/vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { Button } from '@/components/ui/button';
import { adminCopy as copy, adminWelcome } from '@/content/admin';

defineProps<{
    stats: { label: string; value: number }[];
}>();
defineEmits<{ tour: []; create: [] }>();
</script>

<!--
    Masthead with the roses in its margin and the one thing the team does
    most (a new couple) as its button; then the numbers set in type.
-->
<template>
    <div>
        <PageMasthead :title="copy.title" :lede="copy.lede" scene="roses">
            <template #actions>
                <Button size="pill" data-tour="create" @click="$emit('create')">
                    <Plus class="size-4" /> {{ copy.newCoupleShort }}
                </Button>
                <button
                    type="button"
                    class="text-action hit-area"
                    @click="$emit('tour')"
                >
                    <Sparkles class="size-4" /> {{ adminWelcome.open }}
                </button>
            </template>
        </PageMasthead>

        <dl class="figure-row" data-tour="stats">
            <div v-for="stat in stats" :key="stat.label" class="stat-figure">
                <dt class="figure-label">{{ stat.label }}</dt>
                <dd v-count-up="stat.value" class="figure-value">
                    {{ stat.value }}
                </dd>
            </div>
        </dl>
    </div>
</template>
