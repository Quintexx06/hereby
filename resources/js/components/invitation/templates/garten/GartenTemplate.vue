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
import GartenHero from '@/components/invitation/templates/garten/GartenHero.vue';
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
    Garten: a page from a private-press book, for gardens, vineyards and
    estates. A text block with a margin beside it: times, dates and the
    chapters' names stand in the margin, the words in the block. Linden
    Hill's italic, Ysabeau for reading, fig on candle cream; the reply on
    the hedge's dark green, in butter.
-->
<template>
    <main class="invitation-page" data-template="garten">
        <GartenHero
            :names="names"
            :date="date"
            :place="place"
            :cover="photos[0] ?? null"
            :alt="content.photoAlt.value"
        />

        <div class="garten-folio">
            <InvitationLetter :invitation="invitation" :replied="replied">
                <template #signature>
                    <p class="garten-signature">
                        {{ names.first }}
                        <template v-if="names.second">
                            &amp; {{ names.second }}
                        </template>
                    </p>
                </template>
            </InvitationLetter>
        </div>

        <section
            v-if="programme.length"
            id="inv-programme"
            class="garten-folio"
        >
            <h2 class="garten-heading">
                {{ t('invitation.programme_heading') }}
            </h2>
            <InvitationProgramme :items="programme" />
        </section>

        <InvitationEvening :evening="invitation.evening" />

        <InvitationPhotos
            :photos="photos"
            :alt="content.photoAlt.value"
            class="garten-plates"
        />

        <InvitationChapter
            v-if="story"
            :block="story"
            :heading="content.heading(story)"
            class="garten-folio"
        >
            <InvitationStory :block="story" />
        </InvitationChapter>

        <div v-if="practical.length" class="garten-folio">
            <InvitationPractical
                v-for="block in practical"
                :key="block.id"
                :block="block"
                :heading="content.heading(block)"
                :level="2"
            />
        </div>

        <InvitationChapter
            v-if="faq"
            :block="faq"
            :heading="content.heading(faq)"
            class="garten-folio"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <InvitationClosing :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
