<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import TourCard from '@/components/admin/TourCard.vue';
import {
    isShown,
    placeCard,
    shadePath,
    useTourSpotlight,
} from '@/composables/useTourSpotlight';
import { findTourTarget as find } from '@/config/adminTour';
import type { TourStep as Step } from '@/config/adminTour';
import { adminWelcome as copy } from '@/content/admin';

const open = defineModel<boolean>('open', { required: true });
const emit = defineEmits<{ finish: [] }>();

/* Steps whose element is on screen now: the phone's closed nav drops out. */
const steps = ref<Step[]>([]);
const index = ref(0);
const step = computed(() => steps.value[index.value]);
const element = ref<HTMLElement | null>(null);
const { rect, viewport, refresh } = useTourSpotlight(element);

const card = ref<InstanceType<typeof TourCard> | null>(null);
const cardSize = ref({ width: 0, height: 0 });
const position = computed(() =>
    placeCard(rect.value, cardSize.value, viewport.value),
);

async function show(): Promise<void> {
    const target = find(step.value);
    element.value = isShown(target) ? target : null;
    refresh();
    await nextTick();
    const root = card.value?.root;
    cardSize.value = {
        width: root?.offsetWidth ?? 0,
        height: root?.offsetHeight ?? 0,
    };
    root?.focus({ preventScroll: true });
}

function go(to: number): void {
    if (to >= steps.value.length) {
        finish();
    } else {
        index.value = Math.max(0, to);
        show();
    }
}

function finish(): void {
    open.value = false;
    emit('finish');
}

/* A click on a highlighted control does what it does, and ends the tour. */
function onClick(event: MouseEvent): void {
    if (
        'interactive' in step.value &&
        element.value?.contains(event.target as Node)
    ) {
        finish();
    }
}

function onKey(event: KeyboardEvent): void {
    const moves: Record<string, () => void> = {
        Escape: finish,
        ArrowRight: () => go(index.value + 1),
        ArrowLeft: () => go(index.value - 1),
    };
    moves[event.key]?.();
}

function listen(on: boolean): void {
    if (on) {
        document.addEventListener('click', onClick, true);
        document.addEventListener('keydown', onKey);
    } else {
        document.removeEventListener('click', onClick, true);
        document.removeEventListener('keydown', onKey);
    }
}

watch(open, (isOpen) => {
    listen(isOpen);
    element.value = null;

    if (isOpen) {
        steps.value = copy.steps.filter(
            (candidate) => !candidate.target || isShown(find(candidate)),
        );
        index.value = 0;
        nextTick(show);
    }
});

onBeforeUnmount(() => listen(false));
</script>

<!--
    First-visit tour: the page dims, a ring moves from control to control,
    and a card beside it explains each. A highlighted button or link stays
    clickable, so the team can try it right away.
-->
<template>
    <Teleport to="body">
        <Transition name="tour-fade">
            <div v-if="open && step" class="tour">
                <div
                    class="tour-shade"
                    :style="{
                        clipPath: `path(evenodd, '${shadePath(rect, viewport)}')`,
                    }"
                    aria-hidden="true"
                />
                <div
                    v-if="rect"
                    class="tour-ring"
                    :data-interactive="'interactive' in step"
                    :style="{
                        transform: `translate(${rect.x}px, ${rect.y}px)`,
                        width: `${rect.width}px`,
                        height: `${rect.height}px`,
                    }"
                    aria-hidden="true"
                />
                <TourCard
                    ref="card"
                    :step="step"
                    :index="index"
                    :total="steps.length"
                    :style="{
                        transform: `translate(${position.left}px, ${position.top}px)`,
                    }"
                    @back="go(index - 1)"
                    @next="go(index + 1)"
                    @close="finish"
                />
            </div>
        </Transition>
    </Teleport>
</template>
