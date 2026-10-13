<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import SaveBar from '@/components/SaveBar.vue';
import { domainCopy as copy } from '@/content/extras';
import { domain as updateDomain } from '@/routes/weddings/extras';

const props = defineProps<{
    weddingId: number;
    domain: { name: string | null; connected: boolean };
}>();

const form = useForm({ domain: props.domain.name ?? '' });
</script>

<!-- «Eigene Domain»: the couple enters it, the team connects it. -->
<template>
    <form
        class="flex max-w-xl flex-col gap-3"
        @submit.prevent="
            form.put(updateDomain.url(weddingId), { preserveScroll: true })
        "
    >
        <h2 class="title">{{ copy.title }}</h2>
        <label class="flex flex-col gap-1.5">
            <span class="text-sm font-medium">{{ copy.label }}</span>
            <input
                v-model="form.domain"
                :placeholder="copy.placeholder"
                autocomplete="off"
                spellcheck="false"
                class="seating-input"
            />
        </label>
        <InputError :message="form.errors.domain" />
        <p class="field-hint">{{ copy.hint }}</p>
        <p v-if="domain.name" class="publish-note">
            {{ domain.connected ? copy.connected : copy.pending }}
        </p>
        <SaveBar
            :processing="form.processing"
            :dirty="form.isDirty"
            :saved="form.recentlySuccessful"
            :label="copy.save"
            :saved-label="copy.saved"
        />
    </form>
</template>
