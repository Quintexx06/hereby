<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import StudioBrowser from '@/components/marketing/StudioBrowser.vue';
import StudioTabs from '@/components/marketing/StudioTabs.vue';
import { useAutoCycle } from '@/composables/motion/useAutoCycle';
import { studio } from '@/content/landing';

const total = studio.templates.length;
const { index, select, pause, resume } = useAutoCycle(total, 5200);

/** Once the visitor picks a template, the tour stops and the bars go quiet. */
const chosen = ref(false);
const choose = (next: number) => {
    chosen.value = true;
    select(next);
};

const hovered = ref(false);
const inView = ref(false);
const playing = computed(() => !chosen.value && !hovered.value && inView.value);

const theme = ref<string>(studio.themes[0].slug);

const root = ref<HTMLElement | null>(null);
let observer: IntersectionObserver | undefined;
onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => {
            inView.value = Boolean(entry?.isIntersecting);
            (inView.value ? resume : pause)();
        },
        { threshold: 0.35 },
    );
    if (root.value) {
        observer.observe(root.value);
    }
});
onBeforeUnmount(() => observer?.disconnect());

const onEnter = () => {
    hovered.value = true;
    pause();
};
const onLeave = () => {
    hovered.value = false;
    if (inView.value) {
        resume();
    }
};
</script>

<!--
    The three templates as a wide browser window: big tabs switch the
    template (and tour through them on their own until one is picked),
    a few dots in the window's toolbar try another colour world.
-->
<template>
    <section ref="root" class="studio" :data-theme="theme">
        <div class="page-container section flex flex-col items-center gap-10">
            <div
                v-reveal
                class="flex max-w-3xl reveal flex-col items-center gap-5 text-center"
            >
                <h2 class="headline">{{ studio.title }}</h2>
                <p class="lede">{{ studio.lede }}</p>
            </div>

            <StudioTabs
                :templates="studio.templates"
                :active="index"
                :playing="playing"
                :show-progress="!chosen"
                @select="choose"
            />

            <StudioBrowser
                v-model:theme="theme"
                :template="studio.templates[index].slug"
                :themes="studio.themes"
                @mouseenter="onEnter"
                @mouseleave="onLeave"
            />
        </div>
    </section>
</template>
