<script setup lang="ts">
import { useReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';

const { t } = useTrans();
const { form, reply } = useReplyForm();
</script>

<!-- Optional proof by email, with the dates for the calendar (roadmap 1.5). -->
<template>
    <section class="reply-section">
        <div class="field">
            <label for="reply-email" class="field-label">{{
                t('rsvp.email')
            }}</label>
            <input
                id="reply-email"
                v-model="form.email"
                type="email"
                class="reply-input"
                autocomplete="email"
                inputmode="email"
                maxlength="191"
                :disabled="!reply.open"
            />
            <p class="field-hint">{{ t('rsvp.email_hint') }}</p>
        </div>
    </section>
</template>
