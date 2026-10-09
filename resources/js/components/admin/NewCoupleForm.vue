<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { adminCopy as copy } from '@/content/admin';
import { store } from '@/routes/admin/weddings';

const form = useForm({ email: '', partner_one: '', partner_two: '' });

function submit(): void {
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <form class="import-panel" @submit.prevent="submit">
        <div class="flex flex-col gap-1">
            <h2 class="app-section-title">{{ copy.newCouple }}</h2>
            <p class="field-hint">{{ copy.newCoupleHint }}</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
            <div class="field">
                <label for="partner_one" class="field-label">{{
                    copy.partnerOne
                }}</label>
                <Input id="partner_one" v-model="form.partner_one" required />
                <InputError :message="form.errors.partner_one" />
            </div>
            <div class="field">
                <label for="partner_two" class="field-label">{{
                    copy.partnerTwo
                }}</label>
                <Input id="partner_two" v-model="form.partner_two" required />
                <InputError :message="form.errors.partner_two" />
            </div>
        </div>
        <div class="field">
            <label for="couple_email" class="field-label">{{
                copy.email
            }}</label>
            <Input
                id="couple_email"
                v-model="form.email"
                type="email"
                autocomplete="off"
                required
            />
            <InputError :message="form.errors.email" />
        </div>
        <Button
            type="submit"
            size="pill"
            class="self-start"
            :disabled="form.processing"
        >
            <Spinner v-if="form.processing" />
            {{ copy.create }}
        </Button>
    </form>
</template>
