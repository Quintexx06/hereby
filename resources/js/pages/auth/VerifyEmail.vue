<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';

defineOptions({
    layout: {
        title: 'E-Mail-Adresse bestätigen',
        description:
            'Bitte bestätigt eure E-Mail-Adresse über den Link, den wir euch gerade geschickt haben.',
    },
});

defineProps<{
    status?: string;
    email?: string;
}>();
</script>

<!--
    Waiting for the click in the mail: where it went, what to do when it
    does not arrive, a new link, and a way to fix a mistyped address.
-->
<template>
    <Head title="E-Mail-Adresse bestätigen" />

    <div
        v-if="status === 'verification-link-sent'"
        class="mb-4 text-center text-sm font-medium text-success"
    >
        Wir haben euch einen neuen Bestätigungslink geschickt.
    </div>
    <div
        v-else-if="status === 'link-expired'"
        class="mb-4 text-center text-sm font-medium text-destructive"
    >
        Dieser Link ist abgelaufen. Hier bekommt ihr einen neuen.
    </div>

    <p v-if="email" class="mb-6 text-center text-sm text-muted-foreground">
        Gesendet an <strong class="text-foreground">{{ email }}</strong
        >. Nichts angekommen? Schaut auch im Spam-Ordner nach.
    </p>

    <Form
        v-bind="send.form()"
        class="space-y-6 text-center"
        v-slot="{ processing }"
    >
        <Button :disabled="processing" variant="secondary" size="pill">
            <Spinner v-if="processing" />
            Bestätigungs-E-Mail erneut senden
        </Button>

        <div class="flex justify-center gap-6 text-sm">
            <TextLink :href="edit()">Adresse ändern</TextLink>
            <TextLink :href="logout()" as="button">Abmelden</TextLink>
        </div>
    </Form>
</template>
