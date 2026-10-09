<script setup lang="ts">
import { Sparkles } from '@lucide/vue';
import RosesCanvas from '@/components/marketing/RosesCanvas.vue';
import { Button } from '@/components/ui/button';
import { adminCopy as copy, adminWelcome } from '@/content/admin';
import { landingPhotos } from '@/content/landing-photos';

defineProps<{
    stats: { label: string; value: number }[];
}>();
defineEmits<{ tour: [] }>();

const photo = landingPhotos.tableCandles;
</script>

<!-- The admin's opening: candlelight, the roses, and the numbers that matter. -->
<template>
    <section class="admin-hero stage">
        <img
            :src="photo.src"
            alt=""
            class="admin-hero-photo"
            fetchpriority="high"
        />
        <div class="admin-hero-veil" aria-hidden="true" />
        <RosesCanvas />

        <div class="relative flex max-w-lg flex-col items-start gap-4">
            <h1 class="app-title">{{ copy.title }}</h1>
            <p class="text-pretty text-muted-foreground">{{ copy.lede }}</p>
            <Button
                variant="outline"
                size="sm"
                class="rounded-full"
                @click="$emit('tour')"
            >
                <Sparkles />
                {{ adminWelcome.open }}
            </Button>
        </div>

        <dl class="admin-stats">
            <div v-for="stat in stats" :key="stat.label" class="admin-stat">
                <dt class="text-sm text-muted-foreground">{{ stat.label }}</dt>
                <dd class="admin-stat-value">{{ stat.value }}</dd>
            </div>
        </dl>
    </section>
</template>
