<script setup lang="ts">
import { ref } from 'vue';
import InvitationBlocks from '@/components/invitation/InvitationBlocks.vue';
import InvitationCalendar from '@/components/invitation/InvitationCalendar.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationEvents from '@/components/invitation/InvitationEvents.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationGallery from '@/components/invitation/InvitationGallery.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import PanoramaHero from '@/components/invitation/templates/PanoramaHero.vue';
import { useGuestMotion } from '@/composables/motion/useGuestMotion';
import { panoramaMotion } from '@/lib/guest-motion/panorama';
import type { Invitation, SiteSection } from '@/types';

const props = defineProps<{
    invitation: Invitation;
    replied: boolean;
    place: string | null;
}>();

/* Built-in parts the couple left out (Look › Feinschliff). */
const shows = (section: SiteSection): boolean =>
    !props.invitation.wedding.hiddenSections.includes(section);

const root = ref<HTMLElement | null>(null);
useGuestMotion(root, (kit) => {
    if (!kit.reduced && root.value) {
        panoramaMotion(kit, root.value);
    }
});
</script>

<!-- Panorama: cinematic. A running title, the day sideways, the story as film. -->
<template>
    <main ref="root" class="invitation-page" data-template="panorama">
        <PanoramaHero :wedding="invitation.wedding" :place="place" />
        <InvitationLetter :invitation="invitation" :replied="replied" />
        <InvitationEvents :events="invitation.events" scrollable />
        <InvitationEvening :evening="invitation.evening" />
        <InvitationCalendar v-if="shows('calendar')" :invitation="invitation" />
        <InvitationGallery :wedding="invitation.wedding" variant="panorama" />
        <InvitationBlocks :blocks="invitation.blocks" story="film" />
        <InvitationClosing v-if="shows('closing')" :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
