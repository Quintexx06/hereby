<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { nextTick, ref, useTemplateRef } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { deleteCopy } from '@/content/account';

/* No modal and no red box: a quiet action that asks once, inline. */
const asking = ref(false);
const passwordInput = useTemplateRef('passwordInput');

async function ask(): Promise<void> {
    asking.value = true;
    await nextTick();
    passwordInput.value?.focus();
}
</script>

<template>
    <section class="settings-section">
        <Heading
            variant="small"
            :title="deleteCopy.title"
            :description="deleteCopy.lede"
        />

        <button
            v-if="!asking"
            type="button"
            class="link-underline self-start text-sm text-destructive"
            data-test="delete-user-button"
            @click="ask"
        >
            {{ deleteCopy.start }}
        </button>

        <Form
            v-else
            v-bind="ProfileController.destroy.form()"
            reset-on-success
            :options="{ preserveScroll: true }"
            class="flex flex-col gap-4"
            v-slot="{ errors, processing, reset, clearErrors }"
            @error="() => passwordInput?.focus()"
        >
            <div class="field">
                <label for="delete_password" class="field-label">{{
                    deleteCopy.password
                }}</label>
                <PasswordInput
                    id="delete_password"
                    ref="passwordInput"
                    name="password"
                    autocomplete="current-password"
                />
                <InputError :message="errors.password" />
            </div>
            <div class="flex flex-wrap gap-2">
                <Button
                    type="submit"
                    variant="destructive"
                    size="pill"
                    :disabled="processing"
                    data-test="confirm-delete-user-button"
                >
                    {{ deleteCopy.confirm }}
                </Button>
                <Button
                    type="button"
                    variant="ghost"
                    size="pill"
                    @click="
                        () => {
                            clearErrors();
                            reset();
                            asking = false;
                        }
                    "
                >
                    {{ deleteCopy.cancel }}
                </Button>
            </div>
        </Form>
    </section>
</template>
