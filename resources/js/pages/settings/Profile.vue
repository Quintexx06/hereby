<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { account, profileCopy } from '@/content/account';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';

defineOptions({
    layout: {
        breadcrumbs: [{ title: account.title, href: edit() }],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head :title="profileCopy.title" />

    <section class="settings-section">
        <Heading
            variant="small"
            :title="profileCopy.title"
            :description="profileCopy.lede"
        />

        <Form
            v-bind="ProfileController.update.form()"
            class="flex flex-col gap-6"
            v-slot="{ errors, processing }"
        >
            <div class="field">
                <label for="name" class="field-label">{{
                    profileCopy.name
                }}</label>
                <Input
                    id="name"
                    name="name"
                    :default-value="user.name"
                    required
                    autocomplete="name"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="field">
                <label for="email" class="field-label">{{
                    profileCopy.email
                }}</label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    :default-value="user.email"
                    required
                    autocomplete="username"
                />
                <InputError :message="errors.email" />
                <p
                    v-if="page.props.mustVerifyEmail && !user.email_verified_at"
                    class="field-hint"
                >
                    {{ profileCopy.unverified }}
                    <Link
                        :href="send()"
                        as="button"
                        class="link-underline text-foreground"
                    >
                        {{ profileCopy.resend }}
                    </Link>
                </p>
                <p
                    v-if="page.props.status === 'verification-link-sent'"
                    class="text-sm text-success"
                    role="status"
                >
                    {{ profileCopy.resent }}
                </p>
            </div>

            <Button
                size="pill"
                class="self-start"
                :disabled="processing"
                data-test="update-profile-button"
            >
                <Spinner v-if="processing" />
                {{ account.save }}
            </Button>
        </Form>
    </section>

    <DeleteUser />
</template>
