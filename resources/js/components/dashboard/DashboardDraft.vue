<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { draft } from '@/content/dashboard';
import { steps } from '@/content/setup';
import { show } from '@/routes/weddings/setup';
import type { DashboardWedding } from '@/types';

defineProps<{ wedding: DashboardWedding }>();
</script>

<!-- A setup in progress: where they stopped, and the way back in. -->
<template>
    <section class="flex max-w-2xl flex-col gap-8">
        <div class="flex flex-col gap-4">
            <h1 class="app-title">{{ draft.title }}</h1>
            <p class="lede">
                {{ draft.lede(steps[wedding.setup_step ?? 'paar'].label) }}
            </p>
        </div>
        <div
            class="setup-progress max-w-md"
            role="progressbar"
            :aria-valuenow="wedding.setup_position ?? 1"
            aria-valuemin="1"
            :aria-valuemax="wedding.setup_total"
        >
            <span
                v-for="position in wedding.setup_total"
                :key="position"
                class="setup-progress-step"
                :data-state="
                    position <= (wedding.setup_position ?? 1)
                        ? 'done'
                        : 'upcoming'
                "
            />
        </div>
        <Button size="pill" class="self-start" as-child>
            <Link :href="show([wedding.id, wedding.setup_step ?? 'paar'])">{{
                draft.cta
            }}</Link>
        </Button>
    </section>
</template>
