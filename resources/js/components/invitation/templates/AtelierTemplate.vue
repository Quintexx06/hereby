<script setup lang="ts">
import { ref } from 'vue';
import InvitationBlocks from '@/components/invitation/InvitationBlocks.vue';
import InvitationCalendar from '@/components/invitation/InvitationCalendar.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationEvents from '@/components/invitation/InvitationEvents.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationHero from '@/components/invitation/InvitationHero.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationOrnament from '@/components/invitation/InvitationOrnament.vue';
import { useGuestMotion } from '@/composables/motion/useGuestMotion';
import { atelierMotion } from '@/lib/guest-motion/guest';
import type { Invitation } from '@/types';

defineProps<{
    invitation: Invitation;
    replied: boolean;
    place: string | null;
}>();

const root = ref<HTMLElement | null>(null);
useGuestMotion(root, (kit) => {
    if (!kit.reduced && root.value) {
        atelierMotion(kit, root.value);
    }
});
</script>

<!-- Atelier: editorial. Large type, the story as a drawn path. -->
<template>
    <main ref="root" class="invitation-page" data-template="atelier">
        <InvitationHero :wedding="invitation.wedding" :place="place" />
        <InvitationLetter :invitation="invitation" :replied="replied" />
        <InvitationOrnament />
        <InvitationEvents :events="invitation.events" />
        <InvitationEvening :evening="invitation.evening" />
        <InvitationCalendar :invitation="invitation" />
        <InvitationOrnament />
        <InvitationBlocks :blocks="invitation.blocks" />
        <InvitationClosing :invitation="invitation" />
        <InvitationFooter />
    </main>
</template>
