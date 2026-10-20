<script setup lang="ts">
import { ref } from 'vue';
import InvitationActs from '@/components/invitation/InvitationActs.vue';
import InvitationBlocks from '@/components/invitation/InvitationBlocks.vue';
import InvitationCalendar from '@/components/invitation/InvitationCalendar.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationGallery from '@/components/invitation/InvitationGallery.vue';
import InvitationHero from '@/components/invitation/InvitationHero.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationOrnament from '@/components/invitation/InvitationOrnament.vue';
import InvitationTicker from '@/components/invitation/InvitationTicker.vue';
import { useGuestMotion } from '@/composables/motion/useGuestMotion';
import { atelierMotion } from '@/lib/guest-motion/atelier';
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
        atelierMotion(kit, root.value);
    }
});
</script>

<!-- Atelier: a magazine. The photo edge to edge, the day in acts, the story as a drawn path. -->
<template>
    <main ref="root" class="invitation-page" data-template="atelier">
        <InvitationHero :wedding="invitation.wedding" :place="place" />
        <InvitationLetter :invitation="invitation" :replied="replied" />
        <InvitationTicker :events="invitation.events" />
        <InvitationActs
            :events="invitation.events"
            :wedding="invitation.wedding"
        />
        <InvitationEvening :evening="invitation.evening" />
        <InvitationCalendar v-if="shows('calendar')" :invitation="invitation" />
        <InvitationGallery :wedding="invitation.wedding" variant="atelier" />
        <InvitationOrnament v-if="shows('ornaments')" />
        <InvitationBlocks :blocks="invitation.blocks" />
        <InvitationClosing v-if="shows('closing')" :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
