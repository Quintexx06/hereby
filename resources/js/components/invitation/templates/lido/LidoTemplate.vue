<script setup lang="ts">
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
import LidoHero from '@/components/invitation/templates/lido/LidoHero.vue';
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
    Lido: a summer at the lake bath, for lakes, Ticino and feasts by day.
    Gloock for the big words, Familjen Grotesk for the rest; lake-depth ink,
    cobalt, a butter field and the lido's stripe. Photos square and straight
    in paper borders; the questions on butter, the reply on the deep lake.
-->
<template>
    <main class="invitation-page" data-template="lido">
        <LidoHero
            :names="names"
            :date="date"
            :place="place"
            :cover="photos[0] ?? null"
            :alt="content.photoAlt.value"
        />

        <div class="lido-letter">
            <InvitationLetter :invitation="invitation" :replied="replied" />
        </div>

        <section
            v-if="programme.length"
            id="inv-programme"
            class="lido-section"
        >
            <h2 class="lido-heading">
                {{ t('invitation.programme_heading') }}
            </h2>
            <InvitationProgramme :items="programme" />
        </section>

        <InvitationEvening :evening="invitation.evening" />

        <InvitationPhotos
            :photos="photos"
            :alt="content.photoAlt.value"
            class="lido-photos"
        />

        <InvitationChapter
            v-if="story"
            :block="story"
            :heading="content.heading(story)"
            class="lido-section"
        >
            <InvitationStory :block="story" />
        </InvitationChapter>

        <div v-if="practical.length" class="lido-section lido-details">
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
            class="lido-faq"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <div class="lido-stripe" aria-hidden="true" />
        <InvitationClosing :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
