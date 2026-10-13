<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { manualCopy } from '@/content/guests';

/* How to reach a household: email for reminders, phone for the team. */
defineProps<{ emailError?: string; phoneError?: string }>();
const email = defineModel<string>('email', { required: true });
const phone = defineModel<string>('phone', { required: true });
</script>

<template>
    <div class="field">
        <label for="edit_email" class="field-label">{{
            manualCopy.email
        }}</label>
        <Input
            id="edit_email"
            v-model="email"
            type="email"
            autocomplete="off"
        />
        <InputError :message="emailError" />
    </div>
    <div class="field">
        <label for="edit_phone" class="field-label">{{
            manualCopy.phone
        }}</label>
        <Input id="edit_phone" v-model="phone" type="tel" autocomplete="off" />
        <p class="field-hint">{{ manualCopy.phoneHint }}</p>
        <InputError :message="phoneError" />
    </div>
</template>
