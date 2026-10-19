<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import PartnerVenueFields from '@/components/admin/PartnerVenueFields.vue';
import { adminPartners as copy } from '@/content/partners';
import { resend, update } from '@/routes/admin/partners';
import type { AdminPartner, PartnerType } from '@/types';

const props = defineProps<{
    partner: AdminPartner;
    types: Record<PartnerType, string>;
}>();
const open = defineModel<boolean>('open', { required: true });

const form = useForm({
    type: props.partner.type,
    name: props.partner.name,
    login: props.partner.login ?? '',
    email: props.partner.email ?? '',
    phone: props.partner.phone ?? '',
    website: props.partner.website ?? '',
    town: props.partner.town ?? '',
    is_showcase: props.partner.is_showcase,
    pitch_note: props.partner.pitch_note ?? '',
});

const fields = [
    { key: 'email', label: copy.contactEmail, type: 'email' },
    { key: 'phone', label: copy.phone, type: 'tel' },
    { key: 'website', label: copy.website, type: 'url' },
    { key: 'town', label: copy.town, type: 'text' },
] as const;

function save(then?: () => void): void {
    form.transform((data) =>
        props.partner.login ? data : { ...data, login: undefined },
    ).patch(update.url(props.partner.id), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
            then?.();
        },
    });
}

/* A prospect's address may be fresh in the form: save it, then invite. */
function sendAccess(): void {
    save(() =>
        router.post(resend.url(props.partner.id), {}, { preserveScroll: true }),
    );
}
</script>

<!-- The team corrects a partner: name, kind, sign-in, contact, Showcase. -->
<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-xl">
            <DialogTitle class="app-section-title text-2xl">
                {{ copy.editTitle(partner.name) }}
            </DialogTitle>
            <DialogDescription>{{ partner.code }}</DialogDescription>

            <form class="partner-edit" @submit.prevent="save()">
                <fieldset class="chip-row partner-edit-wide">
                    <legend class="field-label mb-2">{{ copy.type }}</legend>
                    <label
                        v-for="(label, value) in types"
                        :key="value"
                        class="chip"
                    >
                        <input
                            v-model="form.type"
                            type="radio"
                            :value="value"
                            class="sr-only"
                        />
                        {{ label }}
                    </label>
                </fieldset>
                <div class="partner-edit-wide grid gap-2">
                    <label for="edit-name" class="field-label">{{
                        copy.name
                    }}</label>
                    <Input id="edit-name" v-model="form.name" required />
                    <InputError :message="form.errors.name" />
                </div>
                <div v-if="partner.login" class="partner-edit-wide grid gap-2">
                    <label for="edit-login" class="field-label">{{
                        copy.login
                    }}</label>
                    <Input
                        id="edit-login"
                        v-model="form.login"
                        type="email"
                        required
                    />
                    <p class="field-hint">{{ copy.loginHint }}</p>
                    <InputError :message="form.errors.login" />
                </div>
                <div
                    v-for="field in fields"
                    :key="field.key"
                    class="grid gap-2"
                >
                    <label :for="`edit-${field.key}`" class="field-label">{{
                        field.label
                    }}</label>
                    <Input
                        :id="`edit-${field.key}`"
                        v-model="form[field.key]"
                        :type="field.type"
                    />
                    <InputError :message="form.errors[field.key]" />
                </div>
                <PartnerVenueFields
                    v-if="form.type === 'venue'"
                    v-model:showcase="form.is_showcase"
                    v-model:note="form.pitch_note"
                    :note-error="form.errors.pitch_note"
                />
                <div
                    class="partner-edit-wide flex flex-wrap items-center justify-between gap-4 pt-2"
                >
                    <button
                        v-if="partner.login || form.email"
                        type="button"
                        class="text-action text-sm"
                        :disabled="form.processing"
                        @click="sendAccess"
                    >
                        {{ partner.login ? copy.resend : copy.inviteLogin }}
                    </button>
                    <Button
                        type="submit"
                        size="pill"
                        class="ml-auto"
                        :disabled="form.processing"
                    >
                        {{ copy.save }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
