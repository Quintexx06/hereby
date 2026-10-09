<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { account, passwordCopy } from '@/content/account';
import { edit } from '@/routes/security';
import type { Props as ManagePasskeysProps } from '@/components/ManagePasskeys.vue';
import ManagePasskeys from '@/components/ManagePasskeys.vue';
import type { Props as ManageTwoFactorProps } from '@/components/ManageTwoFactor.vue';
import ManageTwoFactor from '@/components/ManageTwoFactor.vue';

// oxfmt-ignore
type Props = {
    passwordRules: string;
} & ManagePasskeysProps &
    ManageTwoFactorProps;

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: account.title, href: edit() }],
    },
});
</script>

<template>
    <Head :title="account.nav.security" />

    <section class="settings-section">
        <Heading
            variant="small"
            :title="passwordCopy.title"
            :description="passwordCopy.lede"
        />

        <Form
            v-bind="SecurityController.update.form()"
            :options="{
                preserveScroll: true,
            }"
            reset-on-success
            :reset-on-error="[
                'password',
                'password_confirmation',
                'current_password',
            ]"
            class="flex flex-col gap-6"
            v-slot="{ errors, processing }"
        >
            <div class="field">
                <label for="current_password" class="field-label">{{
                    passwordCopy.current
                }}</label>
                <PasswordInput
                    id="current_password"
                    name="current_password"
                    autocomplete="current-password"
                />
                <InputError :message="errors.current_password" />
            </div>

            <div class="field">
                <label for="password" class="field-label">{{
                    passwordCopy.next
                }}</label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    :passwordrules="props.passwordRules"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="field">
                <label for="password_confirmation" class="field-label">{{
                    passwordCopy.confirm
                }}</label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    :passwordrules="props.passwordRules"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                size="pill"
                class="self-start"
                :disabled="processing"
                data-test="update-password-button"
            >
                <Spinner v-if="processing" />
                {{ account.save }}
            </Button>
        </Form>
    </section>

    <section class="settings-section">
        <ManageTwoFactor
            :canManageTwoFactor="canManageTwoFactor"
            :requiresConfirmation="requiresConfirmation"
            :twoFactorEnabled="twoFactorEnabled"
        />
    </section>

    <section class="settings-section">
        <ManagePasskeys
            :canManagePasskeys="canManagePasskeys"
            :passkeys="passkeys"
        />
    </section>
</template>
