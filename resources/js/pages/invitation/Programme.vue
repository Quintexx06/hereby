<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check } from '@lucide/vue';
import { computed } from 'vue';
import ProgrammeOffers from '@/components/invitation/ProgrammeOffers.vue';
import type { ProgrammeOffer } from '@/components/invitation/ProgrammeOffers.vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import ChoiceChips from '@/components/rsvp/ChoiceChips.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingTemplate } from '@/types';

const props = defineProps<{
    wedding: {
        coupleNames: string;
        template: WeddingTemplate;
    };
    contact: string;
    kinds: string[];
    items: ProgrammeOffer[];
    sent: boolean;
    links: { invitation: string; store: string };
}>();

const { t, localeTag } = useTrans();

const form = useForm({
    kind: 'speech' as string | null,
    title: '',
    minutes: 5,
    contact_name: props.contact,
    notes: '',
});
const kindOptions = computed(() =>
    props.kinds.map((kind) => ({
        value: kind,
        label: t(`invitation.programme.kinds.${kind}`),
    })),
);

function submit(): void {
    form.post(props.links.store, {
        preserveScroll: true,
        onSuccess: () => form.reset('title', 'notes'),
    });
}
</script>

<!--
    «Etwas vorbereiten?»: after the reply, a guest offers a speech, game or
    song for the evening. It goes to the witnesses, never to the couple.
-->
<template>
    <WeddingThemeScope :template="wedding.template" :lang="localeTag()">
        <Head :title="t('invitation.programme.title')" />
        <main class="reply-page">
            <Link :href="links.invitation" class="caption inline-flex gap-1.5">
                <ArrowLeft class="size-4" aria-hidden="true" />
                {{ t('invitation.programme.back') }}
            </Link>
            <header class="flex flex-col gap-3">
                <p class="caption">{{ wedding.coupleNames }}</p>
                <h1 class="headline">{{ t('invitation.programme.title') }}</h1>
                <p class="text-muted-foreground">
                    {{ t('invitation.programme.lede') }}
                </p>
            </header>

            <p v-if="sent" class="reply-summary" role="status">
                <Check class="size-5" aria-hidden="true" />
                {{ t('invitation.programme.sent') }}
            </p>

            <form class="flex flex-col gap-6" @submit.prevent="submit">
                <ChoiceChips
                    v-model="form.kind"
                    name="kind"
                    :label="t('invitation.programme.kind')"
                    :options="kindOptions"
                />
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.programme.what')
                    }}</span>
                    <input
                        v-model="form.title"
                        required
                        maxlength="120"
                        :placeholder="
                            t('invitation.programme.what_placeholder')
                        "
                        class="reply-input"
                    />
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.programme.minutes')
                    }}</span>
                    <input
                        v-model.number="form.minutes"
                        type="number"
                        min="1"
                        max="60"
                        inputmode="numeric"
                        class="reply-input w-32 tabular-nums"
                    />
                    <span
                        v-if="form.errors.minutes"
                        class="text-sm text-destructive"
                        >{{ form.errors.minutes }}</span
                    >
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.programme.contact')
                    }}</span>
                    <input
                        v-model="form.contact_name"
                        required
                        maxlength="80"
                        class="reply-input"
                    />
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.programme.notes')
                    }}</span>
                    <input
                        v-model="form.notes"
                        maxlength="500"
                        :placeholder="
                            t('invitation.programme.notes_placeholder')
                        "
                        class="reply-input"
                    />
                </label>
                <button
                    type="submit"
                    class="reply-send self-start"
                    :disabled="form.processing"
                >
                    {{ t('invitation.programme.submit') }}
                </button>
            </form>

            <ProgrammeOffers :items="items" />
        </main>
    </WeddingThemeScope>
</template>
