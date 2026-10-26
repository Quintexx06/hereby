<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingTemplate } from '@/types';

defineProps<{
    template: WeddingTemplate;
    couple: string;
    link: string;
    stopped: boolean;
    stopUrl: string;
}>();
const { t, localeTag } = useTrans();
</script>

<!--
    From a reminder's «no more reminders» link: first the question with one
    button (a link scanner never presses it), then the confirmation.
-->
<template>
    <WeddingThemeScope :template="template" :lang="localeTag()">
        <Head
            :title="
                t(
                    stopped
                        ? 'rsvp.reminder.stopped_title'
                        : 'rsvp.reminder.stop_title',
                )
            "
        />
        <main class="reply-page items-center justify-center text-center">
            <p class="caption">{{ couple }}</p>
            <template v-if="stopped">
                <h1 class="headline">
                    {{ t('rsvp.reminder.stopped_title') }}
                </h1>
                <p class="max-w-sm text-muted-foreground">
                    {{ t('rsvp.reminder.stopped_body', { couple }) }}
                </p>
                <Link :href="link" class="reply-send">{{
                    t('rsvp.back')
                }}</Link>
            </template>
            <template v-else>
                <h1 class="headline">{{ t('rsvp.reminder.stop_title') }}</h1>
                <p class="max-w-sm text-muted-foreground">
                    {{ t('rsvp.reminder.stop_body', { couple }) }}
                </p>
                <button
                    type="button"
                    class="reply-send"
                    @click="router.post(stopUrl)"
                >
                    {{ t('rsvp.reminder.stop_confirm') }}
                </button>
                <Link :href="link" class="link-underline text-sm">{{
                    t('rsvp.back')
                }}</Link>
            </template>
        </main>
    </WeddingThemeScope>
</template>
