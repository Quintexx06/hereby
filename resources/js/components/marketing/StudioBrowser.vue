<script setup lang="ts">
import MiniAtelier from '@/components/marketing/studio/MiniAtelier.vue';
import MiniGarten from '@/components/marketing/studio/MiniGarten.vue';
import MiniPanorama from '@/components/marketing/studio/MiniPanorama.vue';
import { studio } from '@/content/landing';

defineProps<{
    template: string;
    themes: ReadonlyArray<{ slug: string; name: string }>;
}>();

const theme = defineModel<string>('theme', { required: true });

const minis: Record<string, object> = {
    atelier: MiniAtelier,
    garten: MiniGarten,
    panorama: MiniPanorama,
};
</script>

<!--
    A desktop browser window showing the chosen template's first screen.
    Templates crossfade through a soft blur; the dots in the toolbar
    recolour the window in another colour world.
-->
<template>
    <div class="studio-browser" :data-theme="theme">
        <div class="studio-browser-bar">
            <span class="studio-browser-lights" aria-hidden="true">
                <span /><span /><span />
            </span>
            <span class="studio-browser-url">anna-und-luca.hereby.ch</span>
            <span
                class="studio-dots"
                role="radiogroup"
                :aria-label="studio.colours"
            >
                <button
                    v-for="option in themes"
                    :key="option.slug"
                    type="button"
                    role="radio"
                    class="studio-dot"
                    :data-theme="option.slug"
                    :aria-checked="theme === option.slug"
                    :aria-label="option.name"
                    :title="option.name"
                    @click="theme = option.slug"
                />
            </span>
        </div>
        <div class="studio-browser-screen">
            <Transition name="studio-swap">
                <component :is="minis[template]" :key="template" />
            </Transition>
        </div>
    </div>
</template>
