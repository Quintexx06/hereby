<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { coutureRequest as copy } from '@/content/publish';
import { store } from '@/routes/inquiries';

defineOptions({ inheritAttrs: false });

const user = usePage().props.auth?.user;
const form = useForm({ email: user?.email ?? '', question: '', website: '' });
const sent = ref(false);

function send(): void {
    form.transform((data) => ({
        ...data,
        question: `${copy.prefix}${data.question}`,
    })).post(store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('question');
            sent.value = true;
        },
    });
}
</script>

<!--
    «Anfragen» for Couture: a short form in a popup that lands in the
    team's «Anfragen» and inbox. A mailto link did nothing for people
    without a mail app.
-->
<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button size="pill" variant="outline" v-bind="$attrs">
                {{ copy.ask }}
            </Button>
        </DialogTrigger>
        <DialogContent class="couture-request">
            <template v-if="sent">
                <DialogTitle class="couture-request-title">
                    <Check class="size-5 text-brand" aria-hidden="true" />
                    {{ copy.sentTitle }}
                </DialogTitle>
                <DialogDescription>{{ copy.sentLede }}</DialogDescription>
            </template>
            <form v-else class="flex flex-col gap-5" @submit.prevent="send">
                <div class="flex flex-col gap-2">
                    <DialogTitle class="couture-request-title">
                        {{ copy.title }}
                    </DialogTitle>
                    <DialogDescription>{{ copy.lede }}</DialogDescription>
                </div>
                <div class="grid gap-2">
                    <label for="couture-email" class="field-label">{{
                        copy.email
                    }}</label>
                    <Input
                        id="couture-email"
                        v-model="form.email"
                        type="email"
                        required
                    />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="grid gap-2">
                    <label for="couture-message" class="field-label">{{
                        copy.message
                    }}</label>
                    <textarea
                        id="couture-message"
                        v-model="form.question"
                        rows="5"
                        class="textarea"
                        :placeholder="copy.placeholder"
                        required
                    />
                    <InputError :message="form.errors.question" />
                </div>
                <input
                    v-model="form.website"
                    type="text"
                    name="website"
                    class="sr-only"
                    tabindex="-1"
                    autocomplete="off"
                    aria-hidden="true"
                />
                <Button
                    type="submit"
                    size="pill"
                    class="self-start"
                    :disabled="form.processing"
                >
                    <Spinner v-if="form.processing" /> {{ copy.send }}
                </Button>
            </form>
        </DialogContent>
    </Dialog>
</template>
