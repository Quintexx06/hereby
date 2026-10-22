<script setup lang="ts">
import { computed } from 'vue';
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
import ArveHero from '@/components/invitation/templates/arve/ArveHero.vue';
import { useInvitationContent } from '@/composables/useInvitationContent';
import { useTrans } from '@/composables/useTrans';
import { VENUE_TIME_ZONE } from '@/lib/format';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();
const { t } = useTrans();
const content = useInvitationContent(() => props.invitation);
const { names, date, place, programme, story, faq, practical, photos } =
    content;

/* The day and the evening as two columns: from six o'clock it is evening. */
const hour = (iso: string): number =>
    Number(
        new Intl.DateTimeFormat('en-GB', {
            hour: 'numeric',
            hourCycle: 'h23',
            timeZone: VENUE_TIME_ZONE,
        }).format(new Date(iso)),
    );
const parts = computed(() =>
    [
        {
            key: 'day',
            items: programme.value.filter((i) => hour(i.event.startsAt) < 18),
        },
        {
            key: 'evening',
            items: programme.value.filter((i) => hour(i.event.startsAt) >= 18),
        },
    ].filter((part) => part.items.length),
);
</script>

<!--
    Arve: stone pine and wool, for mountain hotels, huts and alpine lakes.
    Vollkorn from black names to reading text, night-forest green and one
    geranium red. The landscape first, then the names; the day and the
    evening side by side, the evening on the dark.
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
            <InvitationLetter :invitation="invitation" :replied="replied" />
        </div>

        <section v-if="parts.length" id="inv-programme" class="arve-day">
            <h2 class="arve-heading">
                {{ t('invitation.programme_heading') }}
            </h2>
            <div class="arve-day-parts">
                <div
                    v-for="part in parts"
                    :key="part.key"
                    class="arve-day-part"
                    :data-part="part.key"
                >
                    <h3 class="arve-day-label">
                        {{ t(`invitation.day_parts.${part.key}`) }}
                    </h3>
                    <InvitationProgramme :items="part.items" />
                </div>
            </div>
        </section>

        <InvitationEvening :evening="invitation.evening" />

        <InvitationPhotos
            :photos="photos"
            :alt="content.photoAlt.value"
            class="arve-photos"
        />

        <InvitationChapter
            v-if="story"
            :block="story"
            :heading="content.heading(story)"
            class="arve-section"
        >
            <InvitationStory :block="story" />
        </InvitationChapter>

        <div v-if="practical.length" class="arve-section arve-details">
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
            class="arve-section"
        >
            <FaqBlock :block="faq" />
        </InvitationChapter>

        <InvitationClosing :invitation="invitation" />
        <InvitationFooter :wedding="invitation.wedding" />
    </main>
</template>
