<script setup lang="ts">
import {
    computed,
    nextTick,
    onBeforeUnmount,
    onMounted,
    ref,
    watch,
} from 'vue';
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import PremiumScreen from '@/components/marketing/PremiumScreen.vue';
import { premium } from '@/content/landing';
import { chf } from '@/content/publish';

defineProps<{ price: number }>();

const selected = ref<string>(premium.moments[1].key);
const position = computed(() =>
    Math.max(
        0,
        premium.moments.findIndex((moment) => moment.key === selected.value),
    ),
);
const active = computed(() => premium.moments[position.value]);

/* Later in the night slides the new screen up, earlier slides it down. */
const direction = ref(1);
watch(position, (now, before) => (direction.value = now > before ? 1 : -1));

/*
 * A short hover intent, so sweeping across the list does not flick through
 * every screen; a click or keyboard choice switches at once.
 */
let intent: ReturnType<typeof setTimeout> | undefined;
function hover(key: string): void {
    clearTimeout(intent);
    intent = setTimeout(() => (selected.value = key), 70);
}
function cancel(): void {
    clearTimeout(intent);
}
function pick(key: string): void {
    clearTimeout(intent);
    selected.value = key;
}

/* The marker glides along the line to the picked moment. */
const timeline = ref<HTMLElement | null>(null);
const marker = ref({ y: 0, height: 0 });
function measure(): void {
    const button =
        timeline.value?.querySelectorAll<HTMLElement>('.premium-moment')[
            position.value
        ];
    if (button) {
        marker.value = { y: button.offsetTop, height: button.offsetHeight };
    }
}
watch(position, () => nextTick(measure));
onMounted(() => {
    measure();
    window.addEventListener('resize', measure);
});
onBeforeUnmount(() => {
    clearTimeout(intent);
    window.removeEventListener('resize', measure);
});
</script>

<!--
    The Atelier package as one night: four moments on a timeline, and the
    phone shows what guests see at the one picked. A marker glides to the
    moment; the screens cross-fade in the direction of the night.
-->
<template>
    <div class="premium-package">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col gap-3">
                <span class="premium-badge">{{
                    premium.package.badge(chf(price))
                }}</span>
                <h3 class="premium-package-title">
                    {{ premium.package.title }}
                </h3>
                <p class="caption">{{ premium.package.hint }}</p>
            </div>

            <ol
                ref="timeline"
                class="premium-timeline"
                role="tablist"
                :aria-label="premium.package.title"
            >
                <span
                    class="premium-marker"
                    aria-hidden="true"
                    :style="{
                        transform: `translateY(${marker.y}px)`,
                        height: `${marker.height}px`,
                    }"
                />
                <li v-for="moment in premium.moments" :key="moment.key">
                    <button
                        type="button"
                        role="tab"
                        class="premium-moment"
                        :aria-selected="moment.key === selected"
                        @click="pick(moment.key)"
                        @focus="pick(moment.key)"
                        @mouseenter="hover(moment.key)"
                        @mouseleave="cancel"
                    >
                        <span class="premium-time">{{ moment.time }}</span>
                        <span class="flex min-w-0 flex-col gap-1 text-left">
                            <span class="flex items-center gap-3">
                                <HerebyIcon
                                    :name="moment.icon"
                                    class="size-7"
                                />
                                <span class="premium-moment-name">{{
                                    moment.name
                                }}</span>
                            </span>
                            <span class="premium-moment-line">{{
                                moment.line
                            }}</span>
                        </span>
                    </button>
                </li>
            </ol>
        </div>

        <figure class="premium-phone" aria-hidden="true">
            <div class="premium-glow" />
            <div class="phone-frame">
                <div class="premium-screens" :style="{ '--dir': direction }">
                    <Transition name="premium-screen">
                        <PremiumScreen :key="active.key" :moment="active.key" />
                    </Transition>
                </div>
            </div>
            <figcaption class="caption text-center">
                {{ active.time }} · {{ active.name }}
            </figcaption>
        </figure>
    </div>
</template>
