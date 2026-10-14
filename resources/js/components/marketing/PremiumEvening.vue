<script setup lang="ts">
import { computed, ref } from 'vue';
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import PremiumScreen from '@/components/marketing/PremiumScreen.vue';
import { premium } from '@/content/landing';

const selected = ref<string>(premium.moments[1].key);
const active = computed(
    () =>
        premium.moments.find((moment) => moment.key === selected.value) ??
        premium.moments[0],
);
</script>

<!--
    The Atelier package as one night: four moments on a timeline, and the
    phone shows what guests see at the one picked. Moves only on a tap.
-->
<template>
    <div class="premium-package">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col gap-3">
                <span class="premium-badge">{{ premium.package.badge }}</span>
                <h3 class="premium-package-title">
                    {{ premium.package.title }}
                </h3>
                <p class="caption">{{ premium.package.hint }}</p>
            </div>

            <ol
                class="premium-timeline"
                role="tablist"
                :aria-label="premium.package.title"
            >
                <li v-for="moment in premium.moments" :key="moment.key">
                    <button
                        type="button"
                        role="tab"
                        class="premium-moment"
                        :aria-selected="moment.key === selected"
                        @click="selected = moment.key"
                        @mouseenter="selected = moment.key"
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
                <Transition name="preview" mode="out-in">
                    <PremiumScreen :key="active.key" :moment="active.key" />
                </Transition>
            </div>
            <figcaption class="caption text-center">
                {{ active.time }} · {{ active.name }}
            </figcaption>
        </figure>
    </div>
</template>
