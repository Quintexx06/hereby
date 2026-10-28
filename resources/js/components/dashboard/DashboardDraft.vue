<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Check } from '@lucide/vue';
import { computed, onMounted } from 'vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { Button } from '@/components/ui/button';
import { draft } from '@/content/dashboard';
import { landingPhotos } from '@/content/landing-photos';
import { steps } from '@/content/setup';
import { show } from '@/routes/weddings/setup';
import type { DashboardWedding } from '@/types';
import type { SetupStepKey } from '@/types/setup';

const props = defineProps<{ wedding: DashboardWedding }>();

const order = Object.keys(steps) as SetupStepKey[];
const position = computed(() => props.wedding.setup_position ?? 1);
const current = computed(() => props.wedding.setup_step ?? 'paar');

const state = (index: number): 'done' | 'current' | 'upcoming' =>
    index + 1 < position.value
        ? 'done'
        : index + 1 === position.value
          ? 'current'
          : 'upcoming';

/* The step they stopped at loads while they read. */
onMounted(() => void import('@/pages/setup/Step.vue'));
</script>

<!--
    A setup in progress: every step, the ones behind them ticked, where
    they stopped, and the way back in. The photo of the welcome page beside it.
-->
<template>
    <section class="dashboard-start">
        <div class="flex max-w-xl flex-col gap-8">
            <div class="flex flex-col gap-4">
                <h1 class="masthead-title">
                    {{ draft.title(wedding.setup_total - position) }}
                </h1>
                <p class="lede">{{ draft.lede(steps[current].label) }}</p>
            </div>
            <ol class="draft-steps">
                <li
                    v-for="(key, index) in order"
                    :key="key"
                    class="draft-step"
                    :data-state="state(index)"
                    :aria-current="
                        state(index) === 'current' ? 'step' : undefined
                    "
                >
                    <span class="draft-step-mark" aria-hidden="true">
                        <Check
                            v-if="state(index) === 'done'"
                            class="size-3.5"
                        />
                    </span>
                    {{ steps[key].label }}
                </li>
            </ol>
            <Button size="pill" class="start-cta self-start" as-child>
                <Link :href="show([wedding.id, current])">
                    {{ draft.cta }}
                    <ArrowRight class="size-4" aria-hidden="true" />
                </Link>
            </Button>
        </div>
        <figure class="dashboard-start-photo">
            <ResponsivePhoto
                :photo="landingPhotos.tableCandles"
                sizes="(min-width: 1024px) 28rem, 1px"
                class="size-full object-cover"
            />
        </figure>
    </section>
</template>
