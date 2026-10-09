<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import InvitationOpening from '@/components/invitation/InvitationOpening.vue';
import InvitationBlocks from '@/components/invitation/InvitationBlocks.vue';
import PreviewBanner from '@/components/invitation/PreviewBanner.vue';
import InvitationEvents from '@/components/invitation/InvitationEvents.vue';
import InvitationFooter from '@/components/invitation/InvitationFooter.vue';
import InvitationReply from '@/components/invitation/InvitationReply.vue';
import InvitationHero from '@/components/invitation/InvitationHero.vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';
import type { Invitation } from '@/types';

defineProps<{
    invitation: Invitation;
    replied: boolean;
    preview: boolean;
    previewLanguages?: string[];
}>();

const { localeTag } = useTrans();
</script>

<template>
    <WeddingThemeScope :theme="invitation.wedding.theme" :lang="localeTag()">
        <Head :title="invitation.wedding.coupleNames" />
        <PreviewBanner v-if="preview" :languages="previewLanguages ?? []" />
        <InvitationOpening
            :couple="invitation.wedding.coupleNames"
            :date="formatDate(invitation.wedding.date, localeTag())"
        />
        <main class="invitation-page">
            <InvitationHero
                :wedding="invitation.wedding"
                :guests="invitation.guests"
            />
            <InvitationEvents :events="invitation.events" />
            <InvitationBlocks :blocks="invitation.blocks" />
            <InvitationReply :invitation="invitation" :replied="replied" />
            <InvitationFooter />
        </main>
    </WeddingThemeScope>
</template>
