<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import AdminChecklist from '@/components/admin/AdminChecklist.vue';
import AdminHero from '@/components/admin/AdminHero.vue';
import AdminWelcome from '@/components/admin/AdminWelcome.vue';
import NewCoupleForm from '@/components/admin/NewCoupleForm.vue';
import WeddingCard from '@/components/admin/WeddingCard.vue';
import { Sheet, SheetContent } from '@/components/ui/sheet';
import { adminChecklist, adminCopy as copy } from '@/content/admin';
import { useRememberedFlag } from '@/composables/useRememberedFlag';
import type { AdminWedding } from '@/types';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Hochzeiten', href: '' }] },
});

const props = defineProps<{ weddings: AdminWedding[] }>();

const stats = computed(() => {
    const drafts = props.weddings.filter((w) => w.status === 'draft').length;
    const answered = props.weddings.reduce((sum, w) => sum + w.answered, 0);

    return [
        { label: copy.stats.weddings, value: props.weddings.length },
        { label: copy.stats.drafts, value: drafts },
        { label: copy.stats.active, value: props.weddings.length - drafts },
        { label: copy.stats.answered, value: answered },
    ];
});

const tourSeen = useRememberedFlag('hereby.admin.tour-seen');
const checklistHidden = useRememberedFlag('hereby.admin.checklist-hidden');
const tourOpen = ref(false);
const creating = ref(false);

/* The tour opens by itself once per browser; the hero button reopens it. */
onMounted(() => (tourOpen.value = !tourSeen.isSet.value));
</script>

<!--
    One column, top to bottom: masthead with the main action, the numbers,
    the first steps (while any are open), then every wedding as a table.
-->
<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <AdminHero
            :stats="stats"
            @tour="tourOpen = true"
            @create="creating = true"
        />
        <AdminWelcome v-model:open="tourOpen" @finish="tourSeen.set(true)" />

        <AdminChecklist
            v-if="!checklistHidden.isSet.value"
            :weddings="weddings"
            @hide="checklistHidden.set(true)"
        />

        <section class="admin-list">
            <div class="flex items-baseline justify-between gap-4">
                <h2 class="ledger-title">{{ copy.listTitle }}</h2>
                <button
                    v-if="checklistHidden.isSet.value"
                    type="button"
                    class="link-underline hit-area text-sm text-muted-foreground"
                    @click="checklistHidden.set(false)"
                >
                    {{ adminChecklist.show }}
                </button>
            </div>

            <div v-if="weddings.length === 0" class="admin-empty">
                <p class="font-semibold text-foreground">{{ copy.empty }}</p>
                <p class="text-sm">{{ copy.emptyHint }}</p>
                <button
                    type="button"
                    class="text-action hit-area mt-2 self-start text-foreground"
                    @click="creating = true"
                >
                    {{ copy.newCoupleShort }}
                </button>
            </div>
            <ul v-else class="admin-ledger">
                <li
                    class="admin-row admin-wedding admin-row-head"
                    aria-hidden="true"
                >
                    <span>{{ copy.columns.couple }}</span>
                    <span>{{ copy.columns.status }}</span>
                    <span>{{ copy.columns.date }}</span>
                    <span>{{ copy.columns.replies }}</span>
                    <span />
                </li>
                <WeddingCard
                    v-for="wedding in weddings"
                    :key="wedding.id"
                    :wedding="wedding"
                />
            </ul>
        </section>

        <Sheet v-model:open="creating">
            <!-- Utilities, not @apply: they must out-rank the shadcn defaults. -->
            <SheetContent
                class="w-full gap-0 p-0 data-[state=closed]:duration-200 data-[state=open]:duration-300 sm:max-w-md"
            >
                <NewCoupleForm @created="creating = false" />
            </SheetContent>
        </Sheet>
    </div>
</template>
