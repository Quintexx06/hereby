<script setup lang="ts">
import { ref } from 'vue';
import InvitationBlocks from '@/components/invitation/InvitationBlocks.vue';
import InvitationCalendar from '@/components/invitation/InvitationCalendar.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationEvents from '@/components/invitation/InvitationEvents.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationOrnament from '@/components/invitation/InvitationOrnament.vue';
import GartenHero from '@/components/invitation/templates/GartenHero.vue';
import { useGuestMotion } from '@/composables/motion/useGuestMotion';
import { gartenMotion } from '@/lib/guest-motion/garten';
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
        gartenMotion(kit, root.value);
    }
});
</script>

<!-- Garten: romantic and botanical. Arches, branches, a vine through the day. -->
<template>
    <main ref="root" class="invitation-page" data-template="garten">
        <GartenHero :wedding="invitation.wedding" :place="place" />
        <InvitationLetter :invitation="invitation" :replied="replied" />
        <InvitationOrnament v-if="shows('ornaments')" />
        <InvitationEvents :events="invitation.events" />
        <InvitationEvening :evening="invitation.evening" />
        <InvitationCalendar v-if="shows('calendar')" :invitation="invitation" />
        <InvitationOrnament v-if="shows('ornaments')" />
        <InvitationBlocks :blocks="invitation.blocks" story="arches" />
        <InvitationClosing v-if="shows('closing')" :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
