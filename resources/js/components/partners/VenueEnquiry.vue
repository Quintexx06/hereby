<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DatePicker } from '@/components/ui/date-picker';
import { Input } from '@/components/ui/input';
import { venuePage as copy } from '@/content/partners';
import { enquiry } from '@/routes/venues';

const props = defineProps<{ slug: string; venueName: string }>();

const form = useForm({ name: '', email: '', preferred_date: '', message: '' });
const sent = ref(false);

function send(): void {
    form.post(enquiry.url(props.slug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            sent.value = true;
        },
    });
}
</script>

<!--
    «Besichtigung anfragen» on a venue's public page. The thanks shows in
    place of the form: public pages have no toasts.
-->
<template>
    <div v-if="sent" class="venue-enquiry" role="status">
        <p class="title flex items-center gap-2">
            <Check class="size-5 text-brand" aria-hidden="true" />
            {{ copy.thanks(venueName) }}
        </p>
        <p class="text-muted-foreground">{{ copy.thanksLede }}</p>
    </div>
    <form v-else class="venue-enquiry" @submit.prevent="send">
        <h2 class="title">{{ copy.ask }}</h2>
        <p class="text-muted-foreground">{{ copy.askLede }}</p>
        <div class="grid gap-2">
            <label for="enquiry-name" class="field-label">{{
                copy.name
            }}</label>
            <Input id="enquiry-name" v-model="form.name" required />
            <InputError :message="form.errors.name" />
        </div>
        <div class="grid gap-2">
            <label for="enquiry-email" class="field-label">{{
                copy.email
            }}</label>
            <Input
                id="enquiry-email"
                v-model="form.email"
                type="email"
                required
            />
            <InputError :message="form.errors.email" />
        </div>
        <div class="grid gap-2">
            <label for="enquiry-date" class="field-label">{{
                copy.date
            }}</label>
            <DatePicker
                id="enquiry-date"
                v-model="form.preferred_date"
                placeholder="Wann ungefähr?"
            />
            <InputError :message="form.errors.preferred_date" />
        </div>
        <div class="grid gap-2">
            <label for="enquiry-message" class="field-label">{{
                copy.message
            }}</label>
            <textarea
                id="enquiry-message"
                v-model="form.message"
                rows="4"
                class="textarea"
            />
        </div>
        <Button type="submit" size="pill" :disabled="form.processing">
            {{ copy.send }}
        </Button>
        <p class="caption">{{ copy.privacy }}</p>
    </form>
</template>
