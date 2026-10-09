<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Check } from '@lucide/vue';
import { computed } from 'vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import ReplyAllergies from '@/components/rsvp/ReplyAllergies.vue';
import ReplyEvent from '@/components/rsvp/ReplyEvent.vue';
import ReplyExtras from '@/components/rsvp/ReplyExtras.vue';
import ReplyFooter from '@/components/rsvp/ReplyFooter.vue';
import ReplyPlusOne from '@/components/rsvp/ReplyPlusOne.vue';
import { provideReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';
import type { ReplyForm } from '@/types';

const props = defineProps<{ reply: ReplyForm }>();

const { t, localeTag } = useTrans();
const { form } = provideReplyForm(props.reply, t('rsvp.children_menu'));

const everyoneComing = computed(() =>
    Object.values(form.answers).every((item) => item.status === 'attending'),
);

/* One tap for the common case: everybody, everything. */
function allComing(): void {
    Object.values(form.answers).forEach((item) => {
        item.status = 'attending';
    });
}

const lede = computed(() =>
    props.reply.wedding.rsvpDeadline
        ? t('rsvp.lede', {
              date: formatDate(props.reply.wedding.rsvpDeadline, localeTag()),
          })
        : t('rsvp.lede_open'),
);
</script>

<!--
    The 60-second reply (roadmap 1.4). One screen: everyone in one tap, then
    only what applies unfolds. The send button stays at the thumb.
-->
<template>
    <WeddingThemeScope :theme="reply.wedding.theme" :lang="localeTag()">
        <Head :title="`${t('rsvp.title')} ${reply.wedding.coupleNames}`" />
        <main class="reply-page">
            <Link
                :href="reply.links.invitation"
                class="check-label self-start text-muted-foreground"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                {{ t('rsvp.back') }}
            </Link>

            <header class="flex flex-col gap-3">
                <p class="caption">{{ reply.wedding.coupleNames }}</p>
                <h1 class="headline">{{ t('rsvp.title') }}</h1>
                <p class="text-muted-foreground">{{ lede }}</p>
            </header>

            <p v-if="!reply.open" class="reply-summary" role="status">
                {{ t('rsvp.closed', { couple: reply.wedding.coupleNames }) }}
            </p>

            <button
                v-else
                type="button"
                class="reply-all"
                :aria-pressed="everyoneComing"
                @click="allComing"
            >
                <Check
                    v-if="everyoneComing"
                    class="size-4"
                    aria-hidden="true"
                />
                {{ t('rsvp.all_attending') }}
            </button>

            <form class="flex flex-col gap-8" @submit.prevent>
                <ReplyEvent
                    v-for="event in reply.events"
                    :key="event.id"
                    :event="event"
                />
                <ReplyPlusOne />
                <ReplyAllergies />
                <ReplyExtras />
            </form>
        </main>
        <ReplyFooter />
    </WeddingThemeScope>
</template>
