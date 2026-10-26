<script setup lang="ts">
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import GuestScene from '@/components/invitation/GuestScene.vue';
import InvitationChapter from '@/components/invitation/InvitationChapter.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationPractical from '@/components/invitation/InvitationPractical.vue';
import InvitationProgramme from '@/components/invitation/InvitationProgramme.vue';
import InvitationStory from '@/components/invitation/InvitationStory.vue';
import SalonFrame from '@/components/invitation/templates/salon/SalonFrame.vue';
import SalonHang from '@/components/invitation/templates/salon/SalonHang.vue';
import SalonHero from '@/components/invitation/templates/salon/SalonHero.vue';
import SalonSpotlight from '@/components/invitation/templates/salon/SalonSpotlight.vue';
import { useInvitationContent } from '@/composables/useInvitationContent';
import { useTrans } from '@/composables/useTrans';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();
const { t } = useTrans();
const content = useInvitationContent(() => props.invitation);
const { names, date, place, programme, story, faq, practical, photos } =
    content;

/* Two champagne flutes toast above the last question (three.js, lazy). */
const flutes = () =>
    import('@/lib/three/flutes').then((scene) => scene.createFlutes);
</script>

<!--
    Salon: a grand hotel's salon at night, for black tie. The invitation
    hangs on a claret wall: the couple in a gilt frame, the letter on an
    ivory card between two more frames, the day in Ballet and gilt, the
    other photos hung salon-style, flutes above the last question.
-->
<template>
    <main class="invitation-page" data-template="salon">
        <SalonSpotlight />
        <SalonHero
            :names="names"
            :date="date"
            :place="place"
            :cover="photos[0] ?? null"
            :alt="content.photoAlt.value"
        />

        <div class="salon-letter" :data-frames="Math.min(photos.length - 1, 2)">
            <SalonFrame
                v-if="photos[1]"
                :photo="photos[1]"
                :alt="content.photoAlt.value"
                class="salon-letter-frame"
            />
            <article class="salon-card">
                <p class="salon-for">
                    {{ t('invitation.for') }} {{ invitation.household.name }}
                </p>
                <InvitationLetter :invitation="invitation" :replied="replied" />
            </article>
            <SalonFrame
                v-if="photos[2]"
                :photo="photos[2]"
                :alt="content.photoAlt.value"
                class="salon-letter-frame"
            />
        </div>

        <section
            v-if="programme.length"
            id="inv-programme"
            class="salon-section salon-evening"
        >
            <h2 class="salon-heading">
                {{ t('invitation.programme_heading') }}
            </h2>
            <InvitationProgramme :items="programme" />
        </section>

        <InvitationEvening
            :evening="invitation.evening"
            :look="{ section: 'salon-section', heading: 'salon-heading' }"
        />

        <SalonHang :photos="photos" :alt="content.photoAlt.value" :from="3" />

        <InvitationChapter
            v-if="story"
            :block="story"
            :heading="content.heading(story)"
            class="salon-section"
        >
            <InvitationStory :block="story" numerals="roman" />
        </InvitationChapter>

        <div v-if="practical.length" class="salon-section salon-details">
            <InvitationPractical
                v-for="block in practical"
                :key="block.id"
                :block="block"
                :heading="content.heading(block)"
            />
        </div>

        <InvitationChapter
            v-if="faq"
            :block="faq"
            :heading="content.heading(faq)"
            class="salon-section salon-faq"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <div class="salon-finale">
            <GuestScene :load="flutes" class="salon-flutes" />
            <InvitationClosing :invitation="invitation" />
        </div>
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
