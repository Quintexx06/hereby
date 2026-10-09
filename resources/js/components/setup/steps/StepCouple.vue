<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { couple } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';

const form = useSetupForm();
</script>

<template>
    <div
        class="grid items-end gap-4 sm:grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)]"
    >
        <div class="field">
            <label for="partner_one" class="field-label">{{
                couple.first
            }}</label>
            <Input
                id="partner_one"
                v-model="form.partner_one"
                v-focus
                class="input-lg"
                autocomplete="given-name"
                :placeholder="couple.placeholderOne"
                required
            />
        </div>
        <span
            class="hidden pb-3 font-display text-3xl font-semibold sm:block"
            aria-hidden="true"
            >&amp;</span
        >
        <div class="field">
            <label for="partner_two" class="field-label">{{
                couple.second
            }}</label>
            <Input
                id="partner_two"
                v-model="form.partner_two"
                class="input-lg"
                :placeholder="couple.placeholderTwo"
                required
            />
        </div>
        <InputError
            class="sm:col-span-3"
            :message="form.errors.partner_one ?? form.errors.partner_two"
        />
    </div>
</template>
