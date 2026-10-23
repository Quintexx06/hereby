<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, defineAsyncComponent, onMounted } from 'vue';
import InvitationNav from '@/components/invitation/InvitationNav.vue';
import StickyReply from '@/components/invitation/StickyReply.vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import type { Invitation, InvitationDemo, WeddingTemplate } from '@/types';

/* Couple-only; guests never download it. */
const PreviewBanner = defineAsyncComponent(
    () => import('@/components/invitation/PreviewBanner.vue'),
);

const props = defineProps<{
    invitation: Invitation;
    replied: boolean;
    preview: boolean;
    previewLanguages?: string[];
    /** A venue's sales demo or a public sample site: its own banner. */
    demo?: InvitationDemo;
}>();

const { localeTag } = useTrans();

/* Inside the phone on «Inhalte»: no couple bar, no desktop scrollbar. */
const page = usePage();
const embedded = computed(
    () => props.preview && page.url.includes('einbetten=1'),
);
onMounted(() =>
    document.documentElement.classList.toggle(
        'preview-embedded',
        embedded.value,
    ),
);

/*
 * The couple's design (ADR 0015): layout, type and colour, all ours. Each
 * design is its own chunk, so a guest downloads only the one they see; the
 * server renders it and the browser hydrates it once it has arrived.
 */
const templates = {
    salon: defineAsyncComponent(
        () =>
            import('@/components/invitation/templates/salon/SalonTemplate.vue'),
    ),
    raster: defineAsyncComponent(
        () =>
            import('@/components/invitation/templates/raster/RasterTemplate.vue'),
    ),
    garten: defineAsyncComponent(
        () =>
            import('@/components/invitation/templates/garten/GartenTemplate.vue'),
    ),
    arve: defineAsyncComponent(
        () => import('@/components/invitation/templates/arve/ArveTemplate.vue'),
    ),
    lido: defineAsyncComponent(
        () => import('@/components/invitation/templates/lido/LidoTemplate.vue'),
    ),
} satisfies Record<WeddingTemplate, unknown>;
const template = computed(
    () => templates[props.invitation.wedding.template] ?? templates.salon,
);

const canReply = computed(
    () =>
        props.invitation.rsvpOpen &&
        !props.invitation.reply.answered &&
        props.invitation.links !== null,
);
</script>

<!-- A guest's invitation: the couple's design, and the reply bar on phones. -->
<template>
    <WeddingThemeScope
        :template="invitation.wedding.template"
        :lang="localeTag()"
    >
        <Head :title="invitation.wedding.coupleNames" />
        <PreviewBanner
            v-if="preview && !embedded"
            :languages="previewLanguages ?? []"
            :demo="demo"
        />
        <InvitationNav
            v-if="!embedded"
            :invitation="invitation"
            :can-reply="canReply"
        />
        <component :is="template" :invitation="invitation" :replied="replied" />
        <StickyReply
            v-if="canReply && invitation.links"
            :href="invitation.links.reply"
        />
    </WeddingThemeScope>
</template>
