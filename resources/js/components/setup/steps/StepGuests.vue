<script setup lang="ts">
import { watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { estimates, guestsCopy, languages } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import type { Locale } from '@/types/wedding';

const form = useSetupForm();

/* The main language must stay one of the chosen ones. */
watch(
    () => [...form.languages],
    (chosen) => {
        if (chosen.length && !chosen.includes(form.default_locale)) {
            form.default_locale = chosen[0] as Locale;
        }
    },
);
</script>

<template>
    <div class="flex flex-col gap-10">
        <fieldset>
            <legend class="field-label mb-3">{{ guestsCopy.size }}</legend>
            <div class="flex flex-wrap gap-2">
                <label
                    v-for="(label, value) in estimates"
                    :key="value"
                    class="chip"
                >
                    <input
                        v-model="form.guest_estimate"
                        type="radio"
                        name="guest_estimate"
                        :value="value"
                        class="sr-only"
                    />
                    {{ label }}
                </label>
            </div>
            <InputError class="mt-2" :message="form.errors.guest_estimate" />
        </fieldset>

        <fieldset>
            <legend class="field-label mb-3">{{ guestsCopy.languages }}</legend>
            <div class="flex flex-wrap gap-2">
                <label
                    v-for="(label, value) in languages"
                    :key="value"
                    class="chip"
                    :lang="value.replace('_', '-')"
                >
                    <input
                        v-model="form.languages"
                        type="checkbox"
                        :value="value"
                        class="sr-only"
                    />
                    {{ label }}
                </label>
            </div>
            <InputError class="mt-2" :message="form.errors.languages" />
        </fieldset>

        <fieldset v-if="form.languages.length > 1">
            <legend class="field-label mb-1">{{ guestsCopy.main }}</legend>
            <p class="field-hint mb-3">{{ guestsCopy.mainHint }}</p>
            <div class="flex flex-wrap gap-2">
                <label
                    v-for="value in form.languages"
                    :key="value"
                    class="chip"
                >
                    <input
                        v-model="form.default_locale"
                        type="radio"
                        name="default_locale"
                        :value="value"
                        class="sr-only"
                    />
                    {{ languages[value] }}
                </label>
            </div>
            <InputError class="mt-2" :message="form.errors.default_locale" />
        </fieldset>
    </div>
</template>
