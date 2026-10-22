<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import InvitationCalendarLinks from '@/components/invitation/InvitationCalendarLinks.vue';
import { useTrans } from '@/composables/useTrans';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation }>();
const { t } = useTrans();

/* Asks once more while the household can still answer; thanks them after. */
const asks = computed(
    () => props.invitation.rsvpOpen && !props.invitation.reply.answered,
);
</script>

<!-- The page's last word before the footer: the question, and the date into the calendar. -->
<template>
    <section id="inv-closing" class="inv-closing">
        <h2 class="inv-closing-title">
            {{ asks ? t('invitation.closing_title') : t('invitation.see_you') }}
        </h2>
        <p v-if="asks" class="inv-closing-text">
            {{ t('invitation.closing_text') }}
        </p>
        <template v-if="asks">
            <Link
                v-if="invitation.links"
                :href="invitation.links.reply"
                class="inv-closing-button"
                >{{ t('rsvp.cta') }}</Link
            >
            <span v-else class="inv-closing-button" aria-disabled="true">{{
                t('rsvp.cta')
            }}</span>
        </template>
        <InvitationCalendarLinks :invitation="invitation" />
    </section>
</template>
