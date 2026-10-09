<script setup lang="ts">
import { ref } from 'vue';
import AddressCombobox from '@/components/setup/AddressCombobox.vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { venue } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import type { SwissAddress } from '@/types/setup';

const form = useSetupForm();
const manual = ref(Boolean(form.venue_address && !form.venue_reference));

function choose(address: SwissAddress | null): void {
    form.venue_address = address?.street ?? null;
    form.venue_postcode = address?.postcode ?? null;
    form.venue_town = address?.town ?? null;
    form.venue_lat = address?.lat ?? null;
    form.venue_lng = address?.lng ?? null;
    form.venue_reference = address?.reference ?? null;
}
</script>

<template>
    <div class="flex flex-col gap-8">
        <fieldset
            :disabled="form.venue_undecided"
            class="flex flex-col gap-8 disabled:opacity-50"
        >
            <div class="field">
                <label for="venue_name" class="field-label">{{
                    venue.name
                }}</label>
                <Input
                    id="venue_name"
                    v-model="form.venue_name"
                    v-focus
                    class="input-lg"
                    :placeholder="venue.namePlaceholder"
                />
                <InputError :message="form.errors.venue_name" />
            </div>

            <AddressCombobox
                v-if="!manual"
                @select="choose"
                @manual="manual = true"
            />

            <div
                v-else
                class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_7rem_minmax(0,0.8fr)]"
            >
                <div class="field">
                    <label for="venue_address" class="field-label"
                        >Strasse und Nummer</label
                    >
                    <Input
                        id="venue_address"
                        v-model="form.venue_address"
                        class="h-11"
                        autocomplete="address-line1"
                    />
                </div>
                <div class="field">
                    <label for="venue_postcode" class="field-label">PLZ</label>
                    <Input
                        id="venue_postcode"
                        v-model="form.venue_postcode"
                        class="h-11"
                        inputmode="numeric"
                        maxlength="4"
                    />
                </div>
                <div class="field">
                    <label for="venue_town" class="field-label">Ort</label>
                    <Input
                        id="venue_town"
                        v-model="form.venue_town"
                        class="h-11"
                        autocomplete="address-level2"
                    />
                </div>
                <InputError
                    class="sm:col-span-3"
                    :message="form.errors.venue_postcode"
                />
            </div>
        </fieldset>

        <label class="choice">
            <input
                v-model="form.venue_undecided"
                type="checkbox"
                class="sr-only"
            />
            <span class="choice-mark" aria-hidden="true">
                <svg
                    v-if="form.venue_undecided"
                    viewBox="0 0 16 16"
                    class="size-3"
                >
                    <path
                        d="M3 8.5l3 3 7-7"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </span>
            <span class="flex flex-col gap-0.5">
                <span class="font-medium">{{ venue.undecided }}</span>
                <span class="text-sm text-muted-foreground">{{
                    venue.undecidedHint
                }}</span>
            </span>
        </label>
    </div>
</template>
