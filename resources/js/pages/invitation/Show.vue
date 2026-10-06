<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import InvitationEvents from '@/components/invitation/InvitationEvents.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationHero from '@/components/invitation/InvitationHero.vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import type { Invitation } from '@/types';

defineProps<{
    invitation: Invitation;
}>();

const { localeTag } = useTrans();
</script>

<template>
    <WeddingThemeScope :theme="invitation.wedding.theme" :lang="localeTag()">
        <Head :title="invitation.wedding.coupleNames" />
        <main class="invitation-page">
            <InvitationHero
                :wedding="invitation.wedding"
                :guests="invitation.guests"
            />
            <InvitationEvents :events="invitation.events" />
            <InvitationFooter
                :rsvp-deadline="invitation.wedding.rsvpDeadline"
            />
        </main>
    </WeddingThemeScope>
</template>
