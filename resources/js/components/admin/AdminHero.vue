<script setup lang="ts">
import { Sparkles } from '@lucide/vue';
import RosesCanvas from '@/components/marketing/RosesCanvas.vue';
import { adminCopy as copy, adminWelcome } from '@/content/admin';

defineProps<{
    stats: { label: string; value: number }[];
}>();
defineEmits<{ tour: [] }>();
</script>

<!-- Masthead with the roses in its margin, then the numbers set in type. -->
<template>
    <div>
        <header class="masthead admin-masthead">
            <div class="relative z-10 max-w-xl">
                <h1 class="masthead-title">{{ copy.title }}</h1>
                <p class="masthead-meta text-pretty">{{ copy.lede }}</p>
                <p class="masthead-actions">
                    <button
                        type="button"
                        class="text-action hit-area"
                        @click="$emit('tour')"
                    >
                        <Sparkles class="size-4" /> {{ adminWelcome.open }}
                    </button>
                </p>
            </div>
            <RosesCanvas />
        </header>

        <dl class="figure-row">
            <div v-for="stat in stats" :key="stat.label" class="figure">
                <dt class="figure-label">{{ stat.label }}</dt>
                <dd class="figure-value">{{ stat.value }}</dd>
            </div>
        </dl>
    </div>
</template>
