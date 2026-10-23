<script setup lang="ts">
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import InvitationChapter from '@/components/invitation/InvitationChapter.vue';
import InvitationClosing from '@/components/invitation/InvitationClosing.vue';
import InvitationEvening from '@/components/invitation/InvitationEvening.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import InvitationPhotos from '@/components/invitation/InvitationPhotos.vue';
import InvitationPractical from '@/components/invitation/InvitationPractical.vue';
import ArveAscent from '@/components/invitation/templates/arve/ArveAscent.vue';
import ArveHero from '@/components/invitation/templates/arve/ArveHero.vue';
import ArveSignpost from '@/components/invitation/templates/arve/ArveSignpost.vue';
import ArveTopo from '@/components/invitation/templates/arve/ArveTopo.vue';
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
    Arve: the Alps on a Swiss map, for mountain hotels, huts and alpine
    lakes. The landscape under contour lines, the day as a trail signpost,
    the story as an ascent to the summit, trail markers over the practical
    chapters, and the last question under the alpine night.
-->
<template>
    <main class="invitation-page" data-template="arve">
        <ArveHero
            :names="names"
            :date="date"
            :place="place"
            :cover="photos[0] ?? null"
            :alt="content.photoAlt.value"
        />

        <div class="arve-letter">
            <ArveTopo class="arve-letter-topo" />
            <InvitationLetter :invitation="invitation" :replied="replied" />
        </div>

        <section v-if="programme.length" id="inv-programme" class="arve-day">
            <h2 class="arve-heading">
                {{ t('invitation.programme_heading') }}
            </h2>
            <ArveSignpost :items="programme" />
        </section>

        <InvitationEvening :evening="invitation.evening" />

        <InvitationPhotos
            :photos="photos"
            :alt="content.photoAlt.value"
            :count="7"
            class="arve-photos"
        />

        <InvitationChapter
            v-if="story"
            :block="story"
            :heading="content.heading(story)"
            class="arve-section"
        >
            <ArveAscent :block="story" :summit="date.long" />
        </InvitationChapter>

        <div v-if="practical.length" class="arve-section arve-details">
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
            class="arve-section arve-faq"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <div class="arve-night">
            <svg
                class="arve-night-ridge"
                viewBox="0 0 1200 120"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path
                    d="M0 120 L0 92 L90 58 L150 74 L260 18 L330 52 L410 34 L520 86 L600 60 L700 4 L790 50 L860 38 L960 80 L1050 46 L1130 70 L1200 40 L1200 120 Z"
                />
            </svg>
            <InvitationClosing :invitation="invitation" />
            <InvitationFooter :wedding="invitation.wedding" />
        </div>
    </main>
</template>
