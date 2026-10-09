<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { UserPlus, X } from '@lucide/vue';
import { computed, ref, toRef } from 'vue';
import HouseholdEditor from '@/components/guests/editor/HouseholdEditor.vue';
import GuestsToolbar from '@/components/guests/GuestsToolbar.vue';
import HouseholdItem from '@/components/guests/HouseholdItem.vue';
import ImportPanel from '@/components/guests/import/ImportPanel.vue';
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent } from '@/components/ui/sheet';
import { active, dashboardPage } from '@/content/dashboard';
import { guestsPage } from '@/content/guests';
import { useGuestFilter } from '@/composables/useGuestFilter';
import { dashboard } from '@/routes';
import type { GuestEvent, GuestsWedding, HouseholdRow } from '@/types';

const props = defineProps<{
    wedding: GuestsWedding;
    households: HouseholdRow[];
    events: GuestEvent[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: guestsPage.title, href: '' },
        ],
    },
});

const { query, filter, visible } = useGuestFilter(
    toRef(() => props.households),
);
const adding = ref(props.households.length === 0);

const guestCount = computed(() =>
    props.households.reduce(
        (sum, household) => sum + household.guests.length,
        0,
    ),
);

const editingId = ref<number | null>(null);
const editing = computed(() =>
    props.households.find((household) => household.id === editingId.value),
);
</script>

<template>
    <Head :title="guestsPage.title" />

    <div class="app-page gap-10">
        <header class="flex flex-wrap items-end justify-between gap-6">
            <div class="flex flex-col gap-2">
                <h1 class="app-title">
                    {{
                        households.length
                            ? guestsPage.title
                            : guestsPage.emptyTitle
                    }}
                </h1>
                <p class="text-muted-foreground">
                    {{
                        households.length
                            ? active.counts(households.length, guestCount)
                            : guestsPage.emptyLede
                    }}
                </p>
            </div>
            <Button
                v-if="households.length"
                size="pill"
                :variant="adding ? 'outline' : 'default'"
                :aria-expanded="adding"
                @click="adding = !adding"
            >
                <X v-if="adding" class="size-4" />
                <UserPlus v-else class="size-4" />
                {{ adding ? guestsPage.close : guestsPage.add }}
            </Button>
        </header>

        <ImportPanel v-if="adding" :wedding="wedding" @done="adding = false" />

        <section v-if="households.length" class="flex flex-col gap-5">
            <GuestsToolbar
                v-model:query="query"
                v-model:filter="filter"
                :households="households"
            />

            <ul class="border-b">
                <HouseholdItem
                    v-for="household in visible"
                    :key="household.id"
                    :household="household"
                    @edit="editingId = household.id"
                />
            </ul>
            <p v-if="visible.length === 0" class="text-muted-foreground">
                {{ guestsPage.noMatches }}
            </p>
        </section>

        <Sheet
            :open="!!editing"
            @update:open="(open) => !open && (editingId = null)"
        >
            <!-- Utilities, not @apply: they must out-rank the shadcn defaults. -->
            <SheetContent
                class="w-full gap-0 p-0 data-[state=closed]:duration-200 data-[state=open]:duration-300 sm:max-w-lg"
                @open-auto-focus.prevent
            >
                <HouseholdEditor
                    v-if="editing"
                    :key="editing.id"
                    :wedding-id="wedding.id"
                    :household="editing"
                    :events="events"
                    @close="editingId = null"
                />
            </SheetContent>
        </Sheet>
    </div>
</template>
