<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import EveningLocked from '@/components/evening/EveningLocked.vue';
import SeatingHousehold from '@/components/evening/SeatingHousehold.vue';
import type { SeatRow } from '@/components/evening/SeatingHousehold.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import SwitchRow from '@/components/rsvp/SwitchRow.vue';
import SaveBar from '@/components/SaveBar.vue';
import { dashboardPage } from '@/content/dashboard';
import { eveningPage, seatingPage as copy } from '@/content/evening';
import { dashboard } from '@/routes';
import { show as evening } from '@/routes/weddings/evening';
import { update } from '@/routes/weddings/seating';

type SeatingHouseholdProp = {
    id: number;
    name: string;
    guests: (Omit<SeatRow, 'table_name' | 'table_note'> & {
        table_name: string | null;
        table_note: string | null;
    })[];
};

const props = defineProps<{
    wedding: { id: number; couple_names: string };
    unlocked: boolean;
    published: boolean;
    households: SeatingHouseholdProp[];
}>();

setLayoutProps({
    breadcrumbs: [
        { title: dashboardPage.title, href: dashboard() },
        { title: eveningPage.title, href: evening(props.wedding.id) },
        { title: copy.title, href: '' },
    ],
});

const form = useForm({
    published: props.published,
    households: props.households.map((household) => ({
        ...household,
        guests: household.guests.map((guest) => ({
            ...guest,
            table_name: guest.table_name ?? '',
            table_note: guest.table_note ?? '',
        })),
    })),
});

const seats = computed(() => form.households.flatMap((h) => h.guests));
const tables = computed(() => [
    ...new Set(seats.value.map((g) => g.table_name.trim()).filter(Boolean)),
]);

function save(): void {
    form.transform((data) => ({
        published: data.published,
        guests: data.households.flatMap((h) =>
            h.guests.map(({ id, table_name, table_note }) => ({
                id,
                table_name,
                table_note,
            })),
        ),
    })).put(update.url(props.wedding.id), { preserveScroll: true });
}
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <EveningLocked
            v-if="!unlocked"
            :wedding-id="wedding.id"
            preview="seating"
        />

        <form v-else class="flex flex-col gap-8" @submit.prevent="save">
            <SwitchRow
                v-model="form.published"
                :label="copy.publish"
                :hint="copy.publishHint"
            />
            <p class="caption">
                {{
                    copy.summary(
                        tables.length,
                        seats.filter((g) => g.table_name.trim()).length,
                        seats.length,
                    )
                }}
            </p>
            <p v-if="!households.length" class="publish-note">
                {{ copy.empty }}
            </p>

            <datalist id="seating-tables">
                <option v-for="table in tables" :key="table" :value="table" />
            </datalist>
            <SeatingHousehold
                v-for="(household, index) in form.households"
                :key="household.id"
                v-model="form.households[index].guests"
                :name="household.name"
                tables-list-id="seating-tables"
            />

            <SaveBar
                :processing="form.processing"
                :dirty="form.isDirty"
                :saved="form.recentlySuccessful"
                :label="copy.save"
                :saved-label="copy.saved"
            />
        </form>
    </div>
</template>
