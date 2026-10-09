<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import InvitationPreview from '@/components/marketing/InvitationPreview.vue';
import { invitationPanel } from '@/content/dashboard';
import { themes } from '@/content/setup';
import { landingPhotos } from '@/content/landing-photos';
import type { LandingPhoto } from '@/content/landing-photos';
import { previewFromWedding } from '@/lib/preview';
import { sceneSource } from '@/lib/setupScenes';
import { preview } from '@/routes/weddings';
import { show } from '@/routes/weddings/setup';
import type { DashboardWedding, OverviewEvent } from '@/types';

const props = defineProps<{
    wedding: DashboardWedding;
    events: OverviewEvent[];
}>();

const household = computed(() =>
    previewFromWedding(props.wedding, props.events),
);
const photo: LandingPhoto = landingPhotos.tableCandles;
</script>

<!--
    The one night moment on the dashboard: the couple's own invitation rising
    out of a candlelit photo, in their theme. Everything else here is numbers.
-->
<template>
    <section class="invitation-panel stage" aria-labelledby="invitation-panel">
        <img
            :src="sceneSource(photo)"
            alt=""
            class="invitation-panel-photo"
            loading="lazy"
            decoding="async"
        />
        <div class="setup-scene-scrim" aria-hidden="true" />

        <div class="relative flex flex-col gap-1">
            <h2 id="invitation-panel" class="app-section-title">
                {{ invitationPanel.title }}
            </h2>
            <p class="text-sm text-muted-foreground">
                {{ invitationPanel.theme(themes[wedding.theme].name) }}
            </p>
            <p class="mt-2 flex gap-5 text-sm font-medium">
                <a
                    :href="preview.url(wedding.id)"
                    target="_blank"
                    rel="noopener"
                    class="link-underline hit-area"
                    >{{ invitationPanel.preview }}</a
                >
                <Link
                    :href="show([wedding.id, 'look'])"
                    class="link-underline hit-area"
                >
                    {{ invitationPanel.change }}
                </Link>
            </p>
        </div>

        <div class="invitation-panel-phone phone-frame" aria-hidden="true">
            <WeddingThemeScope
                :theme="wedding.theme"
                lang="de-CH"
                :fill="false"
                class="overflow-hidden rounded-[2.1rem]"
            >
                <InvitationPreview :household="household" />
            </WeddingThemeScope>
        </div>
    </section>
</template>
