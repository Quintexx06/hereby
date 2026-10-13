<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { nextTick, onMounted, ref, watch } from 'vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
    label?: string;
    /** A name for screen readers when the group shows no label. */
    labelSr?: string;
}>();

const { isCurrentUrl } = useCurrentUrl();
const page = usePage();

/* One ink pill per group glides to the row under the pointer, then home. */
const list = ref<HTMLElement | null>(null);
const glide = ref({ y: 0, height: 0, on: false });

function glideTo(row: Element | null | undefined): void {
    if (!(row instanceof HTMLElement)) {
        glide.value = { ...glide.value, on: false };

        return;
    }

    glide.value = { y: row.offsetTop, height: row.offsetHeight, on: true };
}

const home = () => glideTo(list.value?.querySelector('[data-active="true"]'));
onMounted(home);
watch(
    () => page.url,
    () => nextTick(home),
);
</script>

<template>
    <nav class="app-nav-group" :aria-label="label ?? labelSr">
        <p v-if="label" class="app-nav-group-label">{{ label }}</p>
        <div ref="list" class="relative" @pointerleave="home">
            <span
                class="app-nav-glide"
                :data-on="glide.on"
                :style="{
                    translate: `0 ${glide.y}px`,
                    height: `${glide.height}px`,
                }"
                aria-hidden="true"
            />
            <ul class="flex flex-col gap-0.5">
                <li v-for="item in items" :key="item.title">
                    <Link
                        :href="item.href"
                        class="app-nav-item"
                        :data-active="isCurrentUrl(item.href)"
                        :aria-current="
                            isCurrentUrl(item.href) ? 'page' : undefined
                        "
                        @pointerenter="glideTo($event.currentTarget as Element)"
                        @focus="glideTo($event.currentTarget as Element)"
                        @blur="home"
                    >
                        <component :is="item.icon" class="app-nav-icon" />
                        <span class="app-nav-label">{{ item.title }}</span>
                        <span v-if="item.badge" class="app-nav-badge">{{
                            item.badge
                        }}</span>
                        <span class="app-nav-tip" aria-hidden="true">{{
                            item.title
                        }}</span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>
