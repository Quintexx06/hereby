<script setup lang="ts">
import { computed } from 'vue';
import InvitationReply from '@/components/invitation/InvitationReply.vue';
import { useTrans } from '@/composables/useTrans';
import { formatList } from '@/lib/format';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();
const { t, localeTag } = useTrans();

const names = computed(() =>
    formatList(
        props.invitation.guests.map((guest) => guest.firstName),
        localeTag(),
    ),
);
</script>

<!-- A few lines to this household, and the way to answer right under them. -->
<template>
    <section id="inv-letter" class="inv-letter">
        <p class="inv-dear">
            {{ t('invitation.dear', { names }) }}
        </p>
        <p class="inv-letter-text">{{ t('invitation.letter') }}</p>
        <InvitationReply :invitation="invitation" :replied="replied" />
    </section>
</template>
