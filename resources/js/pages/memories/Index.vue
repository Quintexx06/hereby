<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import LockedPreview from '@/components/locked/LockedPreview.vue';
import PhotoGrid from '@/components/memories/PhotoGrid.vue';
import UploadLink from '@/components/memories/UploadLink.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { dashboardPage } from '@/content/dashboard';
import { memoriesPage as copy } from '@/content/memories';
import { dashboard } from '@/routes';
import { show as publish } from '@/routes/weddings/publish';
import type { GalleryPhoto } from '@/types';

defineProps<{
    wedding: { id: number; couple_names: string };
    unlocked: boolean;
    uploadLink: string | null;
    total: number;
    photos: GalleryPhoto[];
    pages: { previous: string | null; next: string | null };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" scene="ribbon" />

        <LockedPreview
            v-if="!unlocked"
            kind="photos"
            :note="copy.locked"
            :action="copy.unlock"
            :href="publish(wedding.id).url"
        />

        <template v-else>
            <UploadLink v-if="uploadLink" :link="uploadLink" />

            <section class="flex flex-col gap-5" aria-labelledby="gallery">
                <h2 id="gallery" class="app-section-title">
                    {{ copy.count(total) }}
                </h2>
                <p v-if="total === 0" class="text-muted-foreground">
                    {{ copy.empty }}
                </p>
                <PhotoGrid v-else :wedding-id="wedding.id" :photos="photos" />

                <nav
                    v-if="pages.previous || pages.next"
                    class="flex gap-6"
                    :aria-label="copy.title"
                >
                    <Link
                        v-if="pages.previous"
                        :href="pages.previous"
                        class="text-action hit-area"
                        >{{ copy.newer }}</Link
                    >
                    <Link
                        v-if="pages.next"
                        :href="pages.next"
                        class="text-action hit-area"
                        >{{ copy.older }}</Link
                    >
                </nav>
            </section>
        </template>
    </div>
</template>
