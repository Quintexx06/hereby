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

/* «Liebe Sandra»: the greeting as written, the names in the accent's italic. */
const MARK = '\u0000';
const greeting = computed(() => {
    const key =
        props.invitation.guests.length === 1
            ? 'invitation.dear_one'
            : 'invitation.dear';
    const [before, after = ''] = t(key, { names: MARK }).split(MARK);

    return { before, after };
});
</script>

<!--
    A few lines to this household, and the way to answer right under them.
    A design may sign the letter (the signature slot) before the reply.
-->
<template>
    <section id="inv-letter" class="inv-letter">
        <p class="inv-dear">
            {{ greeting.before }}<span class="inv-dear-names">{{ names }}</span
            >{{ greeting.after }}
        </p>
        <p class="inv-letter-text">{{ t('invitation.letter') }}</p>
        <slot name="signature" />
        <InvitationReply :invitation="invitation" :replied="replied" />
    </section>
</template>
