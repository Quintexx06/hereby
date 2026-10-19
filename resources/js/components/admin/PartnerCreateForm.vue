<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { adminPartners as copy } from '@/content/partners';
import { store } from '@/routes/admin/partners';
import type { PartnerType } from '@/types';

defineProps<{ types: Record<PartnerType, string> }>();

const form = useForm({
    type: 'venue' as PartnerType,
    name: '',
    email: '',
    town: '',
    without_login: false,
});

function create(): void {
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset('name', 'email', 'town'),
    });
}
</script>

<!-- A new partner: with a sign-in, or a prospect to pitch a demo to first. -->
<template>
    <form class="partner-create" @submit.prevent="create">
        <fieldset class="chip-row sm:col-span-3">
            <legend class="field-label mb-2">{{ copy.type }}</legend>
            <label v-for="(label, value) in types" :key="value" class="chip">
                <input
                    v-model="form.type"
                    type="radio"
                    :value="value"
                    class="sr-only"
                />
                {{ label }}
            </label>
        </fieldset>
        <div class="grid gap-2">
            <label for="partner-name" class="field-label">{{
                copy.name
            }}</label>
            <Input id="partner-name" v-model="form.name" required />
            <InputError :message="form.errors.name" />
        </div>
        <div class="grid gap-2">
            <label for="partner-email" class="field-label">
                {{ form.without_login ? copy.prospectEmail : copy.email }}
            </label>
            <Input
                id="partner-email"
                v-model="form.email"
                type="email"
                autocomplete="off"
                :required="!form.without_login"
            />
            <InputError :message="form.errors.email" />
        </div>
        <div class="grid gap-2">
            <label for="partner-town" class="field-label">{{
                copy.town
            }}</label>
            <Input id="partner-town" v-model="form.town" />
        </div>
        <label class="flex items-start gap-3 sm:col-span-2">
            <Checkbox
                :model-value="form.without_login"
                class="mt-0.5"
                @update:model-value="form.without_login = $event === true"
            />
            <span>
                <span class="font-semibold">{{ copy.prospect }}</span>
                <span class="field-hint block">{{ copy.prospectHint }}</span>
            </span>
        </label>
        <Button
            type="submit"
            size="pill"
            class="self-end"
            :disabled="form.processing"
        >
            {{ form.without_login ? copy.createProspect : copy.create }}
        </Button>
    </form>
</template>
