<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import SaveButton from '@/components/SaveButton.vue';
import { Input } from '@/components/ui/input';
import { footerContact as copy } from '@/content/content';
import { contact } from '@/routes/weddings/content';
import type { ContentWedding } from '@/types';

const props = defineProps<{ wedding: ContentWedding }>();
const emit = defineEmits<{ saved: [] }>();

const form = useForm({ ...props.wedding.contact });

const fields = [
    {
        key: 'contact_name',
        label: copy.name,
        type: 'text',
        autocomplete: 'name',
        placeholder: copy.namePlaceholder,
    },
    {
        key: 'contact_email',
        label: copy.email,
        type: 'email',
        autocomplete: 'email',
        placeholder: '',
    },
    {
        key: 'contact_phone',
        label: copy.phone,
        type: 'tel',
        autocomplete: 'tel',
        placeholder: '+41 79 …',
    },
] as const;
</script>

<!-- The site's footer: who guests ask. Saved on its own, like a block. -->
<template>
    <form
        class="block-editor"
        aria-labelledby="footer-contact"
        @submit.prevent="
            form.put(contact.url(wedding.id), {
                preserveScroll: true,
                onSuccess: () => emit('saved'),
            })
        "
    >
        <div class="flex flex-col gap-1">
            <h2 id="footer-contact" class="app-section-title">
                {{ copy.title }}
            </h2>
            <p class="field-hint">{{ copy.hint }}</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div v-for="field in fields" :key="field.key" class="grid gap-2">
                <label :for="field.key" class="field-label">{{
                    field.label
                }}</label>
                <Input
                    :id="field.key"
                    v-model="form[field.key]"
                    :type="field.type"
                    :autocomplete="field.autocomplete"
                    :placeholder="field.placeholder"
                />
                <InputError :message="form.errors[field.key]" />
            </div>
        </div>

        <footer>
            <SaveButton
                :processing="form.processing"
                :dirty="form.isDirty"
                :saved="form.recentlySuccessful"
                :label="copy.save"
                :saved-label="copy.saved"
            />
        </footer>
    </form>
</template>
