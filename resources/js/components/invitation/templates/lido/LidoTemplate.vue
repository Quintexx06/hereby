<script setup lang="ts">
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import InvitationChapter from '@/components/invitation/InvitationChapter.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationPractical from '@/components/invitation/InvitationPractical.vue';
import InvitationStory from '@/components/invitation/InvitationStory.vue';
import LidoHero from '@/components/invitation/templates/lido/LidoHero.vue';
import LidoLanes from '@/components/invitation/templates/lido/LidoLanes.vue';
import LidoShapes from '@/components/invitation/templates/lido/LidoShapes.vue';
import LidoTicket from '@/components/invitation/templates/lido/LidoTicket.vue';
import LidoWaves from '@/components/invitation/templates/lido/LidoWaves.vue';
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
    Shrikhand for the big words, Figtree for the rest; lake ink, cobalt, a
    butter field and tomato. The awning and the sun going down into the
    lake, the letter as an entry ticket, the day in the pool's lanes, the
    photos cut out and floating, the last question on the deep lake.
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

        <div class="lido-section lido-letter">
            <LidoTicket
                :invitation="invitation"
                :replied="replied"
                :date="date"
            />
        </div>

        <section v-if="programme.length" id="inv-programme" class="lido-pool">
            <h2 class="lido-heading">
                {{ t('invitation.programme_heading') }}
            </h2>
            <LidoLanes :items="programme" />
        </section>

        <InvitationEvening :evening="invitation.evening" />

        <LidoShapes :photos="photos" :alt="content.photoAlt.value" />

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
                icon
            />
        </div>

        <InvitationChapter
            v-if="faq"
            :block="faq"
            :heading="content.heading(faq)"
            class="lido-section lido-faq"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <div class="lido-lake">
            <LidoWaves class="lido-lake-edge" />
            <InvitationClosing :invitation="invitation" />
            <InvitationFooter :wedding="invitation.wedding" />
        </div>
    </main>
</template>
