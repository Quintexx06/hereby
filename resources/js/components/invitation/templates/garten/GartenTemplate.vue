<script setup lang="ts">
import { computed, ref } from 'vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationOpening from '@/components/invitation/InvitationOpening.vue';
import GartenBlocks from '@/components/invitation/templates/garten/GartenBlocks.vue';
import GartenCalendar from '@/components/invitation/templates/garten/GartenCalendar.vue';
import GartenClosing from '@/components/invitation/templates/garten/GartenClosing.vue';
import GartenEvents from '@/components/invitation/templates/garten/GartenEvents.vue';
import GartenGallery from '@/components/invitation/templates/garten/GartenGallery.vue';
import GartenHero from '@/components/invitation/templates/garten/GartenHero.vue';
import GartenOrnament from '@/components/invitation/templates/garten/GartenOrnament.vue';
import GartenTicker from '@/components/invitation/templates/garten/GartenTicker.vue';
import { useGuestMotion } from '@/composables/motion/useGuestMotion';
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';
import { gartenMotion } from '@/lib/guest-motion/garten';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();
const { localeTag } = useTrans();

/* Where the day happens: the first part with a place. */
const place = computed(
    () =>
        props.invitation.events.find((event) => event.locationName)
            ?.locationName ?? null,
);

const root = ref<HTMLElement | null>(null);
useGuestMotion(root, (kit) => {
    if (!kit.reduced && root.value) {
        gartenMotion(kit, root.value);
    }
});
</script>

<!--
    Garten: romantic and botanical. The veil parts, the couple's photo under
    an arch with branches and falling petals, the day on a vine, the story
    in garden gates, the rings above the last question.
-->
<template>
    <main ref="root" class="invitation-page" data-template="garten">
        <InvitationOpening
            :couple="invitation.wedding.coupleNames"
            :date="formatDate(invitation.wedding.date, localeTag())"
        />
        <GartenHero :wedding="invitation.wedding" :place="place" />
        <InvitationLetter :invitation="invitation" :replied="replied" />
        <GartenOrnament />
        <GartenEvents :events="invitation.events" />
        <InvitationEvening :evening="invitation.evening" />
        <GartenCalendar :invitation="invitation" />
        <GartenTicker :events="invitation.events" />
        <GartenGallery :wedding="invitation.wedding" />
        <GartenOrnament />
        <GartenBlocks :blocks="invitation.blocks" />
        <GartenClosing :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
