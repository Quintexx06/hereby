<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import EventChoices from '@/components/guests/editor/EventChoices.vue';
import HouseholdLink from '@/components/guests/editor/HouseholdLink.vue';
import PeopleFields from '@/components/guests/PeopleFields.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { Spinner } from '@/components/ui/spinner';
import { editCopy, manualCopy } from '@/content/guests';
import { languages } from '@/content/setup';
import { firstError } from '@/lib/forms';
import { update } from '@/routes/weddings/households';
import type { EditableGuest, GuestEvent, HouseholdRow } from '@/types';

/* Mounted fresh per household (keyed by the page), so the form starts clean. */
const props = defineProps<{
    weddingId: number;
    household: HouseholdRow;
    events: GuestEvent[];
}>();
const emit = defineEmits<{ close: [] }>();

const form = useForm({
    name: props.household.name,
    email: props.household.email ?? '',
    locale: props.household.locale,
    plus_one_allowed: props.household.plus_one_allowed,
    events: [...props.household.event_ids],
    guests: props.household.guests.map(
        ({ id, first_name, last_name, is_child }): EditableGuest => ({
            id,
            first_name,
            last_name,
            is_child,
        }),
    ),
});

function submit(): void {
    form.transform((data) => ({ ...data, email: data.email || null })).put(
        update.url([props.weddingId, props.household.id]),
        { preserveScroll: true, onSuccess: () => emit('close') },
    );
}
</script>

<template>
    <div class="flex min-h-0 flex-1 flex-col">
        <SheetHeader class="px-6 pt-8 pb-2">
            <SheetTitle class="font-display text-2xl">{{
                household.name
            }}</SheetTitle>
            <SheetDescription>{{ editCopy.lede }}</SheetDescription>
        </SheetHeader>

        <form
            id="household-editor"
            class="flex flex-1 flex-col gap-6 overflow-y-auto px-6 py-4"
            @submit.prevent="submit"
        >
            <div class="field">
                <label for="edit_name" class="field-label">{{
                    editCopy.name
                }}</label>
                <Input id="edit_name" v-model="form.name" />
                <InputError :message="form.errors.name" />
            </div>

            <PeopleFields
                v-model:guests="form.guests"
                id-prefix="edit"
                :error="firstError(form.errors, 'guests')"
            />

            <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_10rem]">
                <div class="field">
                    <label for="edit_email" class="field-label">{{
                        manualCopy.email
                    }}</label>
                    <Input
                        id="edit_email"
                        v-model="form.email"
                        type="email"
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="field">
                    <label for="edit_locale" class="field-label">{{
                        manualCopy.language
                    }}</label>
                    <select
                        id="edit_locale"
                        v-model="form.locale"
                        class="select-native"
                    >
                        <option
                            v-for="(label, value) in languages"
                            :key="value"
                            :value="value"
                        >
                            {{ label }}
                        </option>
                    </select>
                </div>
            </div>

            <label class="check-label">
                <input
                    v-model="form.plus_one_allowed"
                    type="checkbox"
                    class="checkbox"
                />
                {{ manualCopy.plusOne }}
            </label>

            <EventChoices
                v-model="form.events"
                :events="events"
                :error="firstError(form.errors, 'events')"
            />

            <HouseholdLink
                :wedding-id="weddingId"
                :household="household"
                @removed="emit('close')"
            />
        </form>

        <footer class="border-t px-6 py-4">
            <Button
                type="submit"
                form="household-editor"
                size="pill"
                class="w-full sm:w-auto"
                :disabled="form.processing"
            >
                <Spinner v-if="form.processing" />
                {{ editCopy.save }}
            </Button>
        </footer>
    </div>
</template>
