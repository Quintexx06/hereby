<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import PeopleFields from '@/components/guests/PeopleFields.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { manualCopy } from '@/content/guests';
import { languages } from '@/content/setup';
import { firstError } from '@/lib/forms';
import { store } from '@/routes/weddings/households';
import type { EditableGuest, GuestsWedding } from '@/types';

const props = defineProps<{ wedding: GuestsWedding }>();
const emit = defineEmits<{ saved: [] }>();

const form = useForm({
    name: '',
    email: '',
    locale: props.wedding.default_locale,
    plus_one_allowed: false,
    guests: [
        { first_name: '', last_name: '', is_child: false },
    ] as EditableGuest[],
});

/* No household name typed: name it after its people, like the import does. */
function householdName(): string {
    const people = form.guests.filter((guest) => guest.first_name.trim());
    const last = people[0]?.last_name?.trim();

    if (form.name.trim()) {
        return form.name.trim();
    }

    return people.length > 1
        ? `${people.map((guest) => guest.first_name.trim()).join(' & ')}${last ? ` ${last}` : ''}`
        : `${people[0]?.first_name ?? ''} ${last ?? ''}`.trim();
}

function submit(): void {
    form.transform((data) => ({
        households: [
            { ...data, name: householdName(), email: data.email || null },
        ],
    })).post(store.url(props.wedding.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('saved');
        },
    });
}
</script>

<template>
    <form class="flex flex-col gap-6" @submit.prevent="submit">
        <div class="field">
            <label for="manual_name" class="field-label">{{
                manualCopy.household
            }}</label>
            <Input
                id="manual_name"
                v-model="form.name"
                :placeholder="manualCopy.householdPlaceholder"
            />
            <p class="field-hint">{{ manualCopy.householdHint }}</p>
            <InputError
                :message="firstError(form.errors, 'households.0.name')"
            />
        </div>

        <PeopleFields
            v-model:guests="form.guests"
            id-prefix="manual"
            :error="firstError(form.errors, 'households.0.guests')"
        />

        <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_12rem]">
            <div class="field">
                <label for="manual_email" class="field-label">{{
                    manualCopy.email
                }}</label>
                <Input
                    id="manual_email"
                    v-model="form.email"
                    type="email"
                    autocomplete="off"
                />
                <InputError
                    :message="firstError(form.errors, 'households.0.email')"
                />
            </div>
            <div class="field">
                <label for="manual_locale" class="field-label">{{
                    manualCopy.language
                }}</label>
                <select
                    id="manual_locale"
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

        <Button
            type="submit"
            size="pill"
            class="self-start"
            :disabled="form.processing"
        >
            <Spinner v-if="form.processing" />
            {{ manualCopy.save }}
        </Button>
    </form>
</template>
