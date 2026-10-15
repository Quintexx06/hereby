<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { KeyRound } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { weddingPartners as copy } from '@/content/partners';
import { store } from '@/routes/weddings/partners';

const props = defineProps<{ weddingId: number }>();

const form = useForm({ code: '' });

function connect(): void {
    form.post(store.url(props.weddingId), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<!-- The partner already has a Hereby code: type it, connect. -->
<template>
    <form class="partner-panel" @submit.prevent="connect">
        <KeyRound class="partner-panel-icon" aria-hidden="true" />
        <div class="flex flex-col gap-1">
            <h2 class="title">{{ copy.codeTitle }}</h2>
            <p class="text-muted-foreground">{{ copy.codeLede }}</p>
        </div>
        <div class="grid gap-2">
            <label for="partner-code" class="field-label">{{
                copy.code
            }}</label>
            <Input
                id="partner-code"
                v-model="form.code"
                :placeholder="copy.codePlaceholder"
                autocomplete="off"
                autocapitalize="characters"
                class="partner-code-input"
            />
            <InputError :message="form.errors.code" />
        </div>
        <Button
            type="submit"
            size="pill"
            class="self-start"
            :disabled="form.processing || form.code.length < 8"
        >
            {{ copy.connect }}
        </Button>
    </form>
</template>
