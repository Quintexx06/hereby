<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import InvitationChapter from '@/components/invitation/InvitationChapter.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationPhotos from '@/components/invitation/InvitationPhotos.vue';
import InvitationPractical from '@/components/invitation/InvitationPractical.vue';
import InvitationProgramme from '@/components/invitation/InvitationProgramme.vue';
import InvitationStory from '@/components/invitation/InvitationStory.vue';
import SalonHero from '@/components/invitation/templates/salon/SalonHero.vue';
import { useInvitationContent } from '@/composables/useInvitationContent';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();
const { t } = useTrans();
const content = useInvitationContent(() => props.invitation);
const { names, date, place, programme, story, faq, practical, photos } =
    content;
</script>

<!--
    Salon: an engraved card for black tie. Centred, symmetrical, ink on
    cotton paper; the card on a dark table to open and close, a black and
    white portrait beside the letter, the day as a menu card.
-->
<template>
    <main class="invitation-page" data-template="salon">
        <SalonHero
            :household="invitation.household.name"
            :names="names"
            :date="date"
            :place="place"
        />

        <div class="salon-letter" :data-photo="photos.length > 0">
            <figure v-if="photos[0]" class="salon-portrait">
                <HeroPhoto
                    :photo="photos[0]"
                    :alt="content.photoAlt.value"
                    eager
                    class="size-full"
                />
            </figure>
            <InvitationLetter :invitation="invitation" :replied="replied" />
        </div>

        <section
            v-if="programme.length"
            id="inv-programme"
            class="salon-section"
        >
            <h2 class="salon-heading">
                {{ t('invitation.programme_heading') }}
            </h2>
            <InvitationProgramme :items="programme" />
        </section>

        <InvitationEvening :evening="invitation.evening" />

        <InvitationPhotos
            :photos="photos"
            :alt="content.photoAlt.value"
            class="salon-photos"
        />

        <InvitationChapter
            v-if="story"
            :block="story"
            :heading="content.heading(story)"
            class="salon-section"
        >
            <InvitationStory :block="story" numerals="roman" />
        </InvitationChapter>

        <div v-if="practical.length" class="salon-details">
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
            class="salon-section"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <div class="salon-table">
            <InvitationClosing :invitation="invitation" />
        </div>
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
