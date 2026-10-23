<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GuestScene from '@/components/invitation/GuestScene.vue';
import { useTrans } from '@/composables/useTrans';
import type { Invitation } from '@/types';

defineProps<{ invitation: Invitation }>();
const { t } = useTrans();

const rings = () =>
    import('@/lib/three/wedding-rings').then(
        (scene) => scene.createWeddingRings,
    );
</script>

<!--
    The last screen asks once more, in the theme's ink, before the footer:
    the two rings turn above the question.
-->
<template>
    <section
        v-if="invitation.rsvpOpen && !invitation.reply.answered"
        id="inv-closing"
        class="inv-closing"
    >
        <GuestScene :load="rings" class="inv-closing-rings" />
        <h2 class="inv-closing-title">{{ t('invitation.closing_title') }}</h2>
        <p class="max-w-md text-pretty opacity-80">
            {{ t('invitation.closing_text') }}
        </p>
        <Link
            v-if="invitation.links"
            :href="invitation.links.reply"
            class="inv-closing-button"
            >{{ t('rsvp.cta') }}</Link
        >
        <span v-else class="inv-closing-button" aria-disabled="true">{{
            t('rsvp.cta')
        }}</span>
    </section>
</template>
