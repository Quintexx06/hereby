<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useTrans } from '@/composables/useTrans';

/*
 * The opening (roadmap 1.3): the couple's names behind a sheer veil in their
 * theme, which parts after a beat. Rendered on the server and driven by CSS
 * alone, so it plays from the first paint, before any script, and ends by
 * itself after ~1.4s. It never catches a tap (pointer-events: none). Script
 * only remembers that it was seen and ends it early on a tap or a key.
 * app.blade.php hides it before paint when seen or when motion is reduced.
 */
defineProps<{ couple: string; date: string }>();
const { t } = useTrans();
const visible = ref(true);

function end(): void {
    visible.value = false;
}

onMounted(() => {
    if (document.documentElement.dataset.openingSeen) {
        visible.value = false;

        return;
    }

    try {
        window.localStorage.setItem(
            `hereby:opening:${window.location.pathname}`,
            '1',
        );
    } catch {
        /* Private mode: the opening may play again, which is harmless. */
    }

    window.addEventListener('pointerdown', end, { once: true });
    window.addEventListener('keydown', end, { once: true });
});

onBeforeUnmount(() => {
    window.removeEventListener('pointerdown', end);
    window.removeEventListener('keydown', end);
});
</script>

<template>
    <div v-if="visible" class="opening" aria-hidden="true">
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
