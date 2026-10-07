<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { ArrowRight, Check } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { faq } from '@/content/landing';
import { store } from '@/routes/inquiries';
</script>

<!-- "Fragt uns": stored, mailed to the team inbox, answered by email. -->
<template>
    <Form
        v-bind="store.form()"
        reset-on-success
        :options="{ preserveScroll: true }"
        class="ask-form"
        v-slot="{ errors, processing, wasSuccessful }"
    >
        <p class="title text-xl">{{ faq.askTitle }}</p>
        <p class="caption -mt-2">{{ faq.askLede }}</p>

        <label class="sr-only" for="ask-email">{{ faq.askEmail }}</label>
        <input
            id="ask-email"
            name="email"
            type="email"
            required
            autocomplete="email"
            :placeholder="faq.askEmail"
            class="ask-field"
        />
        <InputError :message="errors.email" />

        <label class="sr-only" for="ask-question">{{ faq.askQuestion }}</label>
        <textarea
            id="ask-question"
            name="question"
            rows="3"
            required
            :placeholder="faq.askQuestion"
            class="ask-field resize-none"
        />
        <InputError :message="errors.question" />

        <Button
            type="submit"
            size="pill"
            :disabled="processing"
            class="self-start"
        >
            <Spinner v-if="processing" />
            {{ faq.askSubmit }}
            <ArrowRight v-if="!processing" class="size-4" />
        </Button>

        <!-- Honeypot: hidden from people, irresistible to bots. -->
        <input
            name="website"
            type="text"
            tabindex="-1"
            autocomplete="off"
            class="ask-honeypot"
            aria-hidden="true"
        />

        <Transition name="preview">
            <p
                v-if="wasSuccessful"
                class="flex items-center gap-2 text-sm font-medium text-success"
            >
                <Check class="size-4" /> {{ faq.askThanks }}
            </p>
        </Transition>
    </Form>
</template>
