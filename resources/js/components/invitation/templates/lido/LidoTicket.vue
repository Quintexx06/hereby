<script setup lang="ts">
import InvitationLetter from '@/components/invitation/InvitationLetter.vue';
import { useTrans } from '@/composables/useTrans';
import type { Invitation } from '@/types';

defineProps<{
    invitation: Invitation;
    replied: boolean;
    date: { numeric: string };
}>();
const { t } = useTrans();
</script>

<!--
    The letter as an entry ticket to the lake bath: the household's
    greeting and the reply on the ticket, the stub torn along the
    perforation with whom it is for and the day.
-->
<template>
    <div class="lido-ticket">
        <div class="lido-ticket-main">
            <InvitationLetter :invitation="invitation" :replied="replied" />
        </div>
        <div class="lido-ticket-stub" aria-hidden="true">
            <span class="lido-ticket-for"
                >{{ t('invitation.for') }} {{ invitation.household.name }}</span
            >
            <span class="lido-ticket-date">{{ date.numeric }}</span>
        </div>
    </div>
</template>
