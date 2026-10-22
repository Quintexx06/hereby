<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, defineAsyncComponent, onMounted } from 'vue';
import InvitationNav from '@/components/invitation/InvitationNav.vue';
import StickyReply from '@/components/invitation/StickyReply.vue';
import ArveTemplate from '@/components/invitation/templates/arve/ArveTemplate.vue';
import GartenTemplate from '@/components/invitation/templates/garten/GartenTemplate.vue';
import LidoTemplate from '@/components/invitation/templates/lido/LidoTemplate.vue';
import RasterTemplate from '@/components/invitation/templates/raster/RasterTemplate.vue';
import SalonTemplate from '@/components/invitation/templates/salon/SalonTemplate.vue';
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

/* The couple's design (ADR 0015): layout, type and colour, all ours. */
const templates = {
    salon: SalonTemplate,
    raster: RasterTemplate,
    garten: GartenTemplate,
    arve: ArveTemplate,
    lido: LidoTemplate,
} satisfies Record<WeddingTemplate, unknown>;
const template = computed(
    () => templates[props.invitation.wedding.template] ?? SalonTemplate,
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
