<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/partner-join';

defineProps<{
    token: string;
    couple: string;
    name: string;
    email: string;
    typeLabel: string;
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Euer Zugang zu Hereby',
        description:
            'Ein Passwort, und die Hochzeit, die euch eingeladen hat, ist schon verbunden. Für Partner kostenlos.',
    },
});
</script>

<!-- A couple's invitation: choose a password, land with the wedding connected. -->
<template>
    <Head title="Zugang einrichten" />

    <Form
        v-bind="store.form(token)"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <p class="rounded-xl border border-brand/30 bg-brand-muted/40 p-4">
            <strong>{{ couple }}</strong> laden euch als {{ typeLabel }} ein.
        </p>

        <div class="grid gap-2">
            <Label for="name">Name eures Betriebs</Label>
            <Input id="name" name="name" :default-value="name" required />
            <InputError :message="errors.name" />
        </div>

        <div class="grid gap-2">
            <Label for="email">E-Mail-Adresse</Label>
            <Input id="email" type="email" :default-value="email" readonly />
        </div>

        <div class="grid gap-2">
            <Label for="password">Passwort</Label>
            <PasswordInput
                id="password"
                name="password"
                autocomplete="new-password"
                required
                v-focus
                aria-describedby="password-hint"
                :passwordrules="passwordRules"
            />
            <p id="password-hint" class="caption">Mindestens 12 Zeichen.</p>
            <InputError :message="errors.password" />
        </div>

        <div class="grid gap-2">
            <Label for="password_confirmation">Passwort bestätigen</Label>
            <PasswordInput
                id="password_confirmation"
                name="password_confirmation"
                autocomplete="new-password"
                required
                :passwordrules="passwordRules"
            />
        </div>

        <Button type="submit" size="pill" :disabled="processing">
            <Spinner v-if="processing" />
            Zugang einrichten
        </Button>
    </Form>
</template>
