<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Printer } from '@lucide/vue';
import { witnessPage as copy } from '@/content/witness';

export type WitnessArticle = {
    id: number;
    author: string;
    title: string;
    body: string;
    photoUrl: string | null;
    deleteUrl: string;
};

defineProps<{ articles: WitnessArticle[]; printUrl: string }>();

function remove(article: WitnessArticle): void {
    if (window.confirm(copy.removeConfirm)) {
        router.delete(article.deleteUrl, { preserveScroll: true });
    }
}
</script>

<!-- The newspaper's stories: read, remove what doesn't belong, print. -->
<template>
    <section class="flex flex-col gap-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="title">{{ copy.newspaper }}</h2>
                <p class="caption">{{ copy.newspaperHint }}</p>
            </div>
            <a :href="printUrl" target="_blank" class="text-action hit-area">
                <Printer class="size-4" /> {{ copy.newspaperPrint }}
            </a>
        </div>
        <p v-if="!articles.length" class="text-muted-foreground">
            {{ copy.newspaperEmpty }}
        </p>
        <article
            v-for="article in articles"
            :key="article.id"
            class="witness-item items-start"
        >
            <div class="flex min-w-0 flex-col gap-2">
                <p class="font-semibold">{{ article.title }}</p>
                <p class="caption">{{ copy.by(article.author) }}</p>
                <img
                    v-if="article.photoUrl"
                    :src="article.photoUrl"
                    alt=""
                    loading="lazy"
                    class="max-h-48 w-auto rounded-lg object-cover"
                />
                <p class="text-sm whitespace-pre-line text-muted-foreground">
                    {{ article.body }}
                </p>
            </div>
            <button
                type="button"
                class="witness-action shrink-0"
                @click="remove(article)"
            >
                {{ copy.remove }}
            </button>
        </article>
    </section>
</template>
