<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { steps } from '@/content/setup';
import { show } from '@/routes/weddings/setup';
import type { SetupStepKey, SetupStepState } from '@/types/setup';

const props = defineProps<{
    weddingId: number;
    current: SetupStepKey;
    states: SetupStepState[];
}>();

const currentIndex = () =>
    props.states.findIndex((item) => item.value === props.current);

function state(index: number): 'done' | 'current' | 'upcoming' {
    if (index === currentIndex()) {
        return 'current';
    }

    return props.states[index].reachable ? 'done' : 'upcoming';
}
</script>

<!-- Seven segments. Reached steps link back; later ones stay locked. -->
<template>
    <nav aria-label="Fortschritt">
        <ol class="setup-progress">
            <li v-for="(item, index) in states" :key="item.value">
                <Link
                    v-if="item.reachable && item.value !== current"
                    :href="show([weddingId, item.value])"
                    class="setup-progress-step"
                    :data-state="state(index)"
                >
                    <span class="hidden sm:block">{{
                        steps[item.value].label
                    }}</span>
                </Link>
                <span
                    v-else
                    class="setup-progress-step"
                    :data-state="state(index)"
                    :aria-current="item.value === current ? 'step' : undefined"
                >
                    <span class="hidden sm:block">{{
                        steps[item.value].label
                    }}</span>
                </span>
            </li>
        </ol>
    </nav>
</template>
