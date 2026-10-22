<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingTemplate } from '@/types';

defineProps<{
    template: WeddingTemplate;
    couple: string;
    link: string;
}>();
const { t, localeTag } = useTrans();
</script>

<template>
    <WeddingThemeScope :template="template" :lang="localeTag()">
        <Head :title="t('rsvp.reminder.stopped_title')" />
        <main class="reply-page items-center justify-center text-center">
            <p class="caption">{{ couple }}</p>
            <h1 class="headline">{{ t('rsvp.reminder.stopped_title') }}</h1>
            <p class="max-w-sm text-muted-foreground">
                {{ t('rsvp.reminder.stopped_body', { couple }) }}
            </p>
            <Link :href="link" class="reply-send">{{ t('rsvp.back') }}</Link>
        </main>
    </WeddingThemeScope>
</template>
