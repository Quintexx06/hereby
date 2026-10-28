<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, ref } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';
import { prefersReducedMotion } from '@/lib/motion';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation; replied: boolean }>();

const { t, tc, localeTag } = useTrans();
const deadline = computed(() =>
    props.invitation.wedding.rsvpDeadline
        ? formatDate(props.invitation.wedding.rsvpDeadline, localeTag())
        : null,
);

/*
 * Right after a reply the page reopens at the top, and the thanks sits
 * below the hero: bring it into view so the guest sees the answer landed.
 */
const summary = ref<HTMLElement | null>(null);

onMounted(async () => {
    if (!props.replied) {
        return;
    }

    await nextTick();
    summary.value?.scrollIntoView({
        block: 'center',
        behavior: prefersReducedMotion() ? 'auto' : 'smooth',
    });
    summary.value?.focus({ preventScroll: true });
});
</script>

<!-- The way to reply, or what was answered and how to change it. -->
<template>
    <section class="inv-reply">
        <template v-if="invitation.reply.answered">
            <div
                ref="summary"
                class="reply-summary w-full outline-none"
                role="status"
                tabindex="-1"
            >
                <p v-if="replied" class="title relative">
                    <!-- Once, right after a yes: petals open around the thanks. -->
                    <span
                        v-if="invitation.reply.attending > 0"
                        class="reply-burst"
                        aria-hidden="true"
                    >
                        <i
                            v-for="petal in 10"
                            :key="petal"
                            :style="{ '--n': petal }"
                        />
                    </span>
                    {{ t('rsvp.thanks') }}
                </p>
                <p class="font-medium">
                    {{
                        tc('rsvp.summary', invitation.reply.attending, {
                            total: invitation.reply.invited,
                        })
                    }}
                </p>
                <template v-if="invitation.rsvpOpen && invitation.links">
                    <p v-if="deadline" class="text-sm text-muted-foreground">
                        {{ t('rsvp.change_until', { date: deadline }) }}
                    </p>
                    <p class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                        <Link
                            :href="invitation.links.reply"
                            class="link-underline hit-area font-medium"
                        >
                            {{ t('rsvp.change') }}
                        </Link>
                    </p>
                </template>
            </div>
        </template>
        <template v-else-if="invitation.rsvpOpen">
            <Link
                v-if="invitation.links"
                :href="invitation.links.reply"
                class="reply-send"
            >
                {{ t('rsvp.cta') }}
            </Link>
            <!-- The couple's preview: the button shows, but leads nowhere. -->
            <span v-else class="reply-send" aria-disabled="true">{{
                t('rsvp.cta')
            }}</span>
            <p v-if="deadline" class="text-sm text-muted-foreground">
                {{ t('invitation.reply_by', { date: deadline }) }}
            </p>
        </template>
        <p v-else class="text-sm text-muted-foreground">
            {{ t('rsvp.closed', { couple: invitation.wedding.coupleNames }) }}
        </p>
    </section>
</template>
