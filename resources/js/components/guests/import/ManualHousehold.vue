<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Plus, X } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { manualCopy } from '@/content/guests';
import { languages } from '@/content/setup';
import { store } from '@/routes/weddings/households';
import type { GuestsWedding, ImportGuest } from '@/types';

const props = defineProps<{ wedding: GuestsWedding }>();
const emit = defineEmits<{ saved: [] }>();

const form = useForm({
    name: '',
    email: '',
    locale: props.wedding.default_locale,
    plus_one_allowed: false,
    guests: [
        { first_name: '', last_name: '', is_child: false },
    ] as ImportGuest[],
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
                class="h-11"
                :placeholder="manualCopy.householdPlaceholder"
            />
            <p class="field-hint">{{ manualCopy.householdHint }}</p>
        </div>

        <div class="flex flex-col gap-3">
            <div
                v-for="(guest, index) in form.guests"
                :key="index"
                class="grid grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] items-end gap-3"
            >
                <div class="field">
                    <label :for="`manual_first_${index}`" class="field-label">{{
                        manualCopy.firstName
                    }}</label>
                    <Input
                        :id="`manual_first_${index}`"
                        v-model="guest.first_name"
                        class="h-11"
                        required
                    />
                </div>
                <div class="field">
                    <label :for="`manual_last_${index}`" class="field-label">{{
                        manualCopy.lastName
                    }}</label>
                    <Input
                        :id="`manual_last_${index}`"
                        v-model="guest.last_name"
                        class="h-11"
                    />
                </div>
                <div class="flex h-11 items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input
                            v-model="guest.is_child"
                            type="checkbox"
                            class="size-4 accent-foreground"
                        />
                        {{ manualCopy.child }}
                    </label>
                    <button
                        v-if="form.guests.length > 1"
                        type="button"
                        class="icon-button"
                        :aria-label="manualCopy.removePerson"
                        @click="form.guests.splice(index, 1)"
                    >
                        <X class="size-4" />
                    </button>
                </div>
            </div>
            <button
                type="button"
                class="link-underline inline-flex items-center gap-1.5 self-start text-sm"
                @click="
                    form.guests.push({
                        first_name: '',
                        last_name: form.guests[0]?.last_name ?? '',
                        is_child: false,
                    })
                "
            >
                <Plus class="size-4" /> {{ manualCopy.addPerson }}
            </button>
            <InputError
                :message="
                    Object.entries(form.errors).find(([key]) =>
                        key.includes('guests'),
                    )?.[1]
                "
            />
        </div>

        <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_12rem]">
            <div class="field">
                <label for="manual_email" class="field-label">{{
                    manualCopy.email
                }}</label>
                <Input
                    id="manual_email"
                    v-model="form.email"
                    type="email"
                    class="h-11"
                    autocomplete="off"
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

        <label class="inline-flex items-center gap-2 text-sm">
            <input
                v-model="form.plus_one_allowed"
                type="checkbox"
                class="size-4 accent-foreground"
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
