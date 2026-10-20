<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { adminPartners as copy } from '@/content/partners';
import { importMethod } from '@/routes/admin/partners';

const form = useForm({ lines: '' });

function submit(): void {
    form.post(importMethod.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<!-- Prospect venues pasted as a list, one per line, for a round of pitches. -->
<template>
    <details class="partner-import">
        <summary class="font-semibold">{{ copy.importTitle }}</summary>
        <form class="mt-4 grid gap-3" @submit.prevent="submit">
            <p class="field-hint">{{ copy.importHint }}</p>
            <label for="partner-import" class="sr-only">{{
                copy.importLabel
            }}</label>
            <textarea
                id="partner-import"
                v-model="form.lines"
                rows="6"
                class="textarea min-h-32"
                spellcheck="false"
                placeholder="Zum Beispiel:&#10;Hotel Seeblick; Spiez; info@hotel-seeblick.ch; https://hotel-seeblick.ch; Ihr Garten am See …&#10;Gasthof Krone; Bern"
            />
            <InputError :message="form.errors.lines" />
            <Button
                type="submit"
                size="pill"
                class="justify-self-start"
                :disabled="form.processing || !form.lines.trim()"
            >
                {{ copy.importSubmit }}
            </Button>
        </form>
    </details>
</template>
