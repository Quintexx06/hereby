<script setup lang="ts">
import {
    BookHeart,
    Clock,
    Images,
    Mail,
    MessageCircleQuestion,
} from '@lucide/vue';
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import InvitationChapter from '@/components/invitation/InvitationChapter.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationPhotos from '@/components/invitation/InvitationPhotos.vue';
import InvitationPractical from '@/components/invitation/InvitationPractical.vue';
import InvitationProgramme from '@/components/invitation/InvitationProgramme.vue';
import InvitationStory from '@/components/invitation/InvitationStory.vue';
import RasterHero from '@/components/invitation/templates/raster/RasterHero.vue';
import { useInvitationContent } from '@/composables/useInvitationContent';
import { useTrans } from '@/composables/useTrans';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();
const { t } = useTrans();
const content = useInvitationContent(() => props.invitation);
const { names, date, place, programme, story, faq, practical, photos } =
    content;
</script>

<!--
    Raster: the Swiss grid for modern venues and the city. One grotesk,
    concrete paper, graphite ink, one vermilion. Every section numbered in
    the left columns, its content in the middle; the day as a timetable.
-->
<template>
    <main class="invitation-page" data-template="raster">
        <RasterHero
            :names="names"
            :date="date"
            :place="place"
            :cover="photos[0] ?? null"
            :alt="content.photoAlt.value"
        />

        <section class="raster-section" aria-labelledby="raster-letter">
            <h2 id="raster-letter" class="raster-label">
                <Mail class="raster-label-icon" aria-hidden="true" />
                {{ t('invitation.letter_heading') }}
            </h2>
            <InvitationLetter :invitation="invitation" :replied="replied" />
        </section>

        <section
            v-if="programme.length"
            id="inv-programme"
            class="raster-section"
        >
            <h2 class="raster-label">
                <Clock class="raster-label-icon" aria-hidden="true" />
                {{ t('invitation.programme_heading') }}
            </h2>
            <InvitationProgramme :items="programme" icons />
        </section>

        <InvitationEvening :evening="invitation.evening" />

        <section
            v-if="photos.length > 1"
            class="raster-section"
            aria-labelledby="raster-photos"
        >
            <h2 id="raster-photos" class="raster-label">
                <Images class="raster-label-icon" aria-hidden="true" />
                {{ t('invitation.photos_heading') }}
            </h2>
            <InvitationPhotos
                :photos="photos"
                :alt="content.photoAlt.value"
                :count="7"
                class="raster-photos"
            />
        </section>

        <InvitationChapter
            v-if="story"
            :block="story"
            :heading="content.heading(story)"
            :icon="BookHeart"
            class="raster-section"
        >
            <InvitationStory :block="story" />
        </InvitationChapter>

        <InvitationPractical
            v-for="block in practical"
            :key="block.id"
            :block="block"
            :heading="content.heading(block)"
            :level="2"
            icon
            class="raster-section"
        />

        <InvitationChapter
            v-if="faq"
            :block="faq"
            :heading="content.heading(faq)"
            :icon="MessageCircleQuestion"
            class="raster-section"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <InvitationClosing :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
