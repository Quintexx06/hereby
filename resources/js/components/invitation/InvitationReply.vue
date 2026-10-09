<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();

const { t, tc, localeTag } = useTrans();
const deadline = computed(() =>
    props.invitation.wedding.rsvpDeadline
        ? formatDate(props.invitation.wedding.rsvpDeadline, localeTag())
        : null,
);
</script>

<!-- The way to reply, or what was answered and how to change it. -->
<template>
    <section class="flex flex-col items-center gap-4 pt-16 text-center">
        <template v-if="invitation.reply.answered">
            <div class="reply-summary w-full" role="status">
                <p v-if="replied" class="title">{{ t('rsvp.thanks') }}</p>
                <p class="font-medium">
                    {{
                        tc('rsvp.summary', invitation.reply.attending, {
                            total: invitation.reply.invited,
                        })
                    }}
                </p>
                <template v-if="invitation.rsvpOpen">
                    <p v-if="deadline" class="text-sm text-muted-foreground">
                        {{ t('rsvp.change_until', { date: deadline }) }}
                    </p>
                    <Link
                        :href="invitation.links.reply"
                        class="link-underline hit-area font-medium"
                    >
                        {{ t('rsvp.change') }}
                    </Link>
                </template>
            </div>
        </template>
        <template v-else-if="invitation.rsvpOpen">
            <Link :href="invitation.links.reply" class="reply-send">
                {{ t('rsvp.cta') }}
            </Link>
            <p v-if="deadline" class="text-sm text-muted-foreground">
                {{ t('invitation.reply_by', { date: deadline }) }}
            </p>
        </template>
        <p v-else class="text-sm text-muted-foreground">
            {{ t('rsvp.closed', { couple: invitation.wedding.coupleNames }) }}
        </p>
    </section>
</template>
