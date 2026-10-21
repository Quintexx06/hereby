<script setup lang="ts">
import { computed, defineAsyncComponent, onMounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import InvitationNav from '@/components/invitation/InvitationNav.vue';
import InvitationOpening from '@/components/invitation/InvitationOpening.vue';
import StickyReply from '@/components/invitation/StickyReply.vue';
import AtelierTemplate from '@/components/invitation/templates/AtelierTemplate.vue';
import GartenTemplate from '@/components/invitation/templates/GartenTemplate.vue';
import PanoramaTemplate from '@/components/invitation/templates/PanoramaTemplate.vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';
import type { Invitation, WeddingTemplate } from '@/types';

/* Couple-only; guests never download it. */
const PreviewBanner = defineAsyncComponent(
    () => import('@/components/invitation/PreviewBanner.vue'),
);

const props = defineProps<{
    invitation: Invitation;
    replied: boolean;
    preview: boolean;
    previewLanguages?: string[];
    /** A venue's sales demo (VenueDemoController): its own banner. */
    demo?: { venue: string; template: string; more: string };
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

/* Where the day happens: the first part with a place. */
const place = computed(
    () =>
        props.invitation.events.find((event) => event.locationName)
            ?.locationName ?? null,
);
/* Layout and motion follow the couple's template; colours follow the theme. */
const templates = {
    atelier: AtelierTemplate,
    garten: GartenTemplate,
    panorama: PanoramaTemplate,
} satisfies Record<WeddingTemplate, unknown>;
const template = computed(
    () => templates[props.invitation.wedding.template] ?? AtelierTemplate,
);

const canReply = computed(
    () =>
        props.invitation.rsvpOpen &&
        !props.invitation.reply.answered &&
        props.invitation.links !== null,
);
</script>

<!--
    A guest's invitation: the couple's theme (colours) around their template
    (layout and motion), the opening veil, and the reply bar on phones.
-->
<template>
    <WeddingThemeScope
        :theme="invitation.wedding.theme"
        :accent="invitation.wedding.accentColor"
        :lang="localeTag()"
    >
        <Head :title="invitation.wedding.coupleNames" />
        <PreviewBanner
            v-if="preview && !embedded"
            :languages="previewLanguages ?? []"
            :demo="demo"
        />
        <InvitationOpening
            v-if="!invitation.wedding.hiddenSections.includes('opening')"
            :couple="invitation.wedding.coupleNames"
            :date="formatDate(invitation.wedding.date, localeTag())"
        />
        <InvitationNav
            v-if="!embedded"
            :invitation="invitation"
            :can-reply="canReply"
        />
        <component
            :is="template"
            :invitation="invitation"
            :replied="replied"
            :place="place"
        />
        <StickyReply
            v-if="canReply && invitation.links"
            :href="invitation.links.reply"
        />
    </WeddingThemeScope>
</template>
