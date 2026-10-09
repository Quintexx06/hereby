<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useTrans } from '@/composables/useTrans';

/*
 * The opening (roadmap 1.3): the couple's names behind a sheer veil in their
 * theme, which parts after a beat. CSS only, so the page stays under two
 * seconds. Once per link, never under reduced motion, and any tap or key
 * skips it: it never stands between a guest and the reply.
 */
defineProps<{ couple: string; date: string }>();
const { t } = useTrans();

const storageKey = `hereby:opening:${window.location.pathname}`;
const seen = (): boolean => {
    try {
        return window.localStorage.getItem(storageKey) === '1';
    } catch {
        return false;
    }
};
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const state = ref<'closed' | 'open' | 'gone'>(
    reduced || seen() ? 'gone' : 'closed',
);
const timers: number[] = [];

function finish(): void {
    state.value = 'gone';
    timers.forEach((timer) => window.clearTimeout(timer));

    try {
        window.localStorage.setItem(storageKey, '1');
    } catch {
        /* Private mode: the opening may play again, which is harmless. */
    }
}

const skip = (event: Event) => {
    event.preventDefault();
    finish();
};

onMounted(() => {
    if (state.value === 'gone') {
        return;
    }

    window.addEventListener('keydown', finish, { once: true });
    timers.push(window.setTimeout(() => (state.value = 'open'), 450));
    timers.push(window.setTimeout(finish, 1350));
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', finish);
    timers.forEach((timer) => window.clearTimeout(timer));
});
</script>

<template>
    <div
        v-if="state !== 'gone'"
        class="opening"
        :data-state="state"
        aria-hidden="true"
        @pointerdown="skip"
    >
        <span class="opening-panel opening-panel-left" />
        <span class="opening-panel opening-panel-right" />
        <p class="opening-names">
            <span class="font-display">{{ couple }}</span>
            <span class="text-base font-medium tracking-normal text-brand">{{
                date
            }}</span>
        </p>
        <span class="opening-skip">{{ t('invitation.skip') }}</span>
    </div>
</template>
