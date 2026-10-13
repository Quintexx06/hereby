<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingTheme } from '@/types';

const props = defineProps<{
    wedding: { coupleNames: string; theme: WeddingTheme };
    storeUrl: string;
    sent: boolean;
}>();

const { t, localeTag } = useTrans();

const form = useForm<{
    author_name: string;
    title: string;
    body: string;
    photo: File | null;
}>({ author_name: '', title: '', body: '', photo: null });

function pick(event: Event): void {
    form.photo = (event.target as HTMLInputElement).files?.[0] ?? null;
}

function submit(): void {
    form.post(props.storeUrl, {
        preserveScroll: true,
        onSuccess: () => form.reset('title', 'body', 'photo'),
    });
}
</script>

<!--
    «Hochzeitszeitung»: one screen on the phone. Name, headline, story,
    maybe a photo. No account; it goes to the witnesses, not the couple.
-->
<template>
    <WeddingThemeScope :theme="wedding.theme" :lang="localeTag()">
        <Head :title="t('invitation.newspaper.title')" />
        <main class="reply-page">
            <header class="flex flex-col gap-3">
                <p class="caption">{{ wedding.coupleNames }}</p>
                <h1 class="headline">{{ t('invitation.newspaper.title') }}</h1>
                <p class="text-muted-foreground">
                    {{
                        t('invitation.newspaper.lede', {
                            couple: wedding.coupleNames,
                        })
                    }}
                </p>
            </header>

            <p v-if="sent" class="reply-summary" role="status">
                <Check class="size-5" aria-hidden="true" />
                {{ t('invitation.newspaper.sent') }}
            </p>

            <form class="flex flex-col gap-6" @submit.prevent="submit">
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.newspaper.author')
                    }}</span>
                    <input
                        v-model="form.author_name"
                        required
                        maxlength="80"
                        class="reply-input"
                    />
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.newspaper.headline')
                    }}</span>
                    <input
                        v-model="form.title"
                        required
                        maxlength="120"
                        :placeholder="
                            t('invitation.newspaper.headline_placeholder')
                        "
                        class="reply-input"
                    />
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.newspaper.body')
                    }}</span>
                    <textarea
                        v-model="form.body"
                        required
                        maxlength="4000"
                        rows="8"
                        class="reply-input h-auto py-3"
                    />
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-medium">{{
                        t('invitation.newspaper.photo')
                    }}</span>
                    <input
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="text-sm"
                        @change="pick"
                    />
                    <span
                        v-if="form.errors.photo"
                        class="text-sm text-destructive"
                        >{{ form.errors.photo }}</span
                    >
                </label>
                <button
                    type="submit"
                    class="reply-send self-start"
                    :disabled="form.processing"
                >
                    {{
                        sent
                            ? t('invitation.newspaper.more')
                            : t('invitation.newspaper.submit')
                    }}
                </button>
            </form>
        </main>
    </WeddingThemeScope>
</template>
