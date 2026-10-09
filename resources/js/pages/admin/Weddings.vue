<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import AdminChecklist from '@/components/admin/AdminChecklist.vue';
import AdminHero from '@/components/admin/AdminHero.vue';
import AdminWelcome from '@/components/admin/AdminWelcome.vue';
import NewCoupleForm from '@/components/admin/NewCoupleForm.vue';
import WeddingCard from '@/components/admin/WeddingCard.vue';
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

/* The tour opens by itself once per browser; the hero button reopens it. */
onMounted(() => (tourOpen.value = !tourSeen.isSet.value));
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <AdminHero :stats="stats" @tour="tourOpen = true" />
        <AdminWelcome v-model:open="tourOpen" @finish="tourSeen.set(true)" />

        <div class="admin-grid">
            <section class="flex min-w-0 flex-col gap-5">
                <h2 class="app-section-title">{{ copy.listTitle }}</h2>

                <div v-if="weddings.length === 0" class="admin-empty">
                    <p class="font-semibold">{{ copy.empty }}</p>
                    <p class="text-sm text-muted-foreground">
                        {{ copy.emptyHint }}
                    </p>
                </div>
                <ul v-else class="flex flex-col gap-3">
                    <WeddingCard
                        v-for="wedding in weddings"
                        :key="wedding.id"
                        :wedding="wedding"
                    />
                </ul>
            </section>

            <aside class="admin-aside">
                <AdminChecklist
                    v-if="!checklistHidden.isSet.value"
                    :weddings="weddings"
                    @hide="checklistHidden.set(true)"
                />
                <NewCoupleForm />
                <button
                    v-if="checklistHidden.isSet.value"
                    type="button"
                    class="link-underline hit-area self-start text-sm text-muted-foreground"
                    @click="checklistHidden.set(false)"
                >
                    {{ adminChecklist.show }}
                </button>
            </aside>
        </div>
    </div>
</template>
