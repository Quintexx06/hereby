<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import { computed } from 'vue';
import EveningLocked from '@/components/evening/EveningLocked.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { dashboardPage } from '@/content/dashboard';
import { eveningPage as copy } from '@/content/evening';
import { dashboard } from '@/routes';
import { edit as groupPhotos } from '@/routes/weddings/group-photos';
import { edit as programme } from '@/routes/weddings/programme';
import { edit as seating } from '@/routes/weddings/seating';
import { edit as wayHome } from '@/routes/weddings/way-home';

type EveningStatus = {
    seating: { published: boolean; seated: number; guests: number };
    wayHome: number;
    groupPhotos: number;
    programme: { total: number; accepted: number };
};

const props = defineProps<{
    wedding: { id: number; couple_names: string };
    unlocked: boolean;
    status: EveningStatus;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});

/* Each tool for the day, with where it stands; set like a programme. */
const rows = computed(() => [
    {
        key: 'seating',
        href: seating(props.wedding.id),
        ...copy.sections.seating,
        status: copy.seatingStatus(props.status.seating),
    },
    {
        key: 'programme',
        href: programme(props.wedding.id),
        ...copy.sections.programme,
        status: copy.programmeStatus(props.status.programme),
    },
    {
        key: 'groupPhotos',
        href: groupPhotos(props.wedding.id),
        ...copy.sections.groupPhotos,
        status: copy.groupPhotosStatus(props.status.groupPhotos),
    },
    {
        key: 'wayHome',
        href: wayHome(props.wedding.id),
        ...copy.sections.wayHome,
        status: copy.wayHomeStatus(props.status.wayHome),
    },
]);
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" scene="ribbon" />

        <EveningLocked v-if="!unlocked" :wedding-id="wedding.id" />

        <section class="ledger-section">
            <Link
                v-for="row in rows"
                :key="row.key"
                :href="row.href"
                class="ledger-link group"
            >
                <span class="grid gap-1 sm:grid-cols-[12rem_minmax(0,1fr)]">
                    <span class="text-lg font-semibold">{{ row.title }}</span>
                    <span class="flex flex-col gap-0.5">
                        <span class="text-muted-foreground">{{
                            row.body
                        }}</span>
                        <span v-if="unlocked" class="caption">{{
                            row.status
                        }}</span>
                    </span>
                </span>
                <ArrowRight class="ledger-arrow" aria-hidden="true" />
            </Link>
        </section>
    </div>
</template>
