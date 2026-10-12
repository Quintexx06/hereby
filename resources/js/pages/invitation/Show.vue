<script setup lang="ts">
import { computed, defineAsyncComponent } from 'vue';
import { Head } from '@inertiajs/vue3';
import InvitationBlocks from '@/components/invitation/InvitationBlocks.vue';
import InvitationCalendar from '@/components/invitation/InvitationCalendar.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvents from '@/components/invitation/InvitationEvents.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationHero from '@/components/invitation/InvitationHero.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationOpening from '@/components/invitation/InvitationOpening.vue';
import InvitationOrnament from '@/components/invitation/InvitationOrnament.vue';
import StickyReply from '@/components/invitation/StickyReply.vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';
import type { Invitation } from '@/types';

/* Couple-only; guests never download it. */
const PreviewBanner = defineAsyncComponent(
    () => import('@/components/invitation/PreviewBanner.vue'),
);

const props = defineProps<{
    invitation: Invitation;
    replied: boolean;
    preview: boolean;
    previewLanguages?: string[];
}>();

const { localeTag } = useTrans();

/* Where the day happens: the first part with a place. */
const place = computed(
    () =>
        props.invitation.events.find((event) => event.locationName)
            ?.locationName ?? null,
);
const canReply = computed(
    () =>
        props.invitation.rsvpOpen &&
        !props.invitation.reply.answered &&
        props.invitation.links !== null,
);
</script>

<!--
    A guest's invitation, told like the landing page: the names large, a few
    lines to this household with the way to answer, then the day, their
    story as a path, the practical chapters and one last "Kommt ihr?".
-->
<template>
    <WeddingThemeScope :theme="invitation.wedding.theme" :lang="localeTag()">
        <Head :title="invitation.wedding.coupleNames" />
        <PreviewBanner v-if="preview" :languages="previewLanguages ?? []" />
        <InvitationOpening
            :couple="invitation.wedding.coupleNames"
            :date="formatDate(invitation.wedding.date, localeTag())"
        />
        <main class="invitation-page">
            <InvitationHero :wedding="invitation.wedding" :place="place" />
            <InvitationLetter :invitation="invitation" :replied="replied" />
            <InvitationOrnament />
            <InvitationEvents :events="invitation.events" />
            <InvitationCalendar :invitation="invitation" />
            <InvitationOrnament />
            <InvitationBlocks :blocks="invitation.blocks" />
            <InvitationClosing :invitation="invitation" />
            <InvitationFooter />
        </main>
        <StickyReply
            v-if="canReply && invitation.links"
            :href="invitation.links.reply"
        />
    </WeddingThemeScope>
</template>
