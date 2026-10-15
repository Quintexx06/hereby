<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Send } from '@lucide/vue';
import { nextTick, useTemplateRef } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { weddingPartners as copy } from '@/content/partners';
import { store } from '@/routes/weddings/partners/invites';
import type { PartnerType } from '@/types';

const props = defineProps<{ weddingId: number }>();

const form = useForm({ type: 'venue' as PartnerType, name: '', email: '' });
const panel = useTemplateRef<HTMLFormElement>('panel');
const types = Object.keys(copy.roles) as PartnerType[];

/** Called from a role card: preselect the role and bring the form into view. */
async function choose(type: PartnerType): Promise<void> {
    form.type = type;
    await nextTick();
    panel.value?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    panel.value?.querySelector<HTMLInputElement>('#invite-name')?.focus({
        preventScroll: true,
    });
}

function send(): void {
    form.post(store.url(props.weddingId), {
        preserveScroll: true,
        onSuccess: () => form.reset('name', 'email'),
    });
}

defineExpose({ choose });
</script>

<!-- The partner is not on Hereby yet: we mail them a link to join. -->
<template>
    <form ref="panel" class="partner-panel" @submit.prevent="send">
        <Send class="partner-panel-icon" aria-hidden="true" />
        <div class="flex flex-col gap-1">
            <h2 class="title">{{ copy.inviteTitle }}</h2>
            <p class="text-muted-foreground">{{ copy.inviteLede }}</p>
        </div>
        <fieldset class="chip-row min-w-0">
            <legend class="field-label mb-2">{{ copy.inviteType }}</legend>
            <label v-for="type in types" :key="type" class="chip">
                <input
                    v-model="form.type"
                    type="radio"
                    :value="type"
                    class="sr-only"
                />
                {{ copy.roles[type].label }}
            </label>
        </fieldset>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="grid gap-2">
                <label for="invite-name" class="field-label">{{
                    copy.inviteName
                }}</label>
                <Input
                    id="invite-name"
                    v-model="form.name"
                    :placeholder="copy.inviteNamePlaceholder"
                    required
                />
                <InputError :message="form.errors.name" />
            </div>
            <div class="grid gap-2">
                <label for="invite-email" class="field-label">{{
                    copy.inviteEmail
                }}</label>
                <Input
                    id="invite-email"
                    v-model="form.email"
                    type="email"
                    autocomplete="off"
                    required
                />
                <InputError :message="form.errors.email" />
            </div>
        </div>
        <Button
            type="submit"
            size="pill"
            class="self-start"
            :disabled="form.processing"
        >
            {{ copy.inviteSend }}
        </Button>
    </form>
</template>
