<script setup lang="ts">
import { DatePicker } from '@/components/ui/date-picker';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BriefColors from '@/components/bespoke/BriefColors.vue';
import InputError from '@/components/InputError.vue';
import SaveButton from '@/components/SaveButton.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { bespoke } from '@/content/bespoke';
import { submit, update } from '@/routes/weddings/bespoke';
import type { Brief } from '@/types';

const props = defineProps<{
    weddingId: number;
    brief: Brief;
    owned: boolean;
}>();
const copy = bespoke.form;
const tomorrow = new Date(Date.now() + 86_400_000).toISOString().slice(0, 10);
const page = usePage();
const submitError = computed(
    () => page.props.errors?.brief ?? page.props.errors?.wishes ?? null,
);

const form = useForm({
    wishes: props.brief.wishes ?? '',
    colors: [...props.brief.colors],
    mood: props.brief.mood ?? '',
    links: props.brief.links ?? '',
    ready_by: props.brief.ready_by ?? '',
});

function save(): void {
    form.put(update.url(props.weddingId), { preserveScroll: true });
}

/* Saves what is written, then sends it to the team. */
function send(): void {
    form.put(update.url(props.weddingId), {
        preserveScroll: true,
        onSuccess: () =>
            router.post(
                submit.url(props.weddingId),
                {},
                { preserveScroll: true },
            ),
    });
}
</script>

<!-- The brief: wishes, colours, mood, links and a date. Saved as drafts. -->
<template>
    <form class="brief-form" @submit.prevent="save">
        <h2 class="app-section-title">{{ copy.title }}</h2>
        <div class="grid gap-2">
            <label for="wishes" class="field-label">{{ copy.wishes }}</label>
            <p class="field-hint">{{ copy.wishesHint }}</p>
            <textarea
                id="wishes"
                v-model="form.wishes"
                rows="6"
                class="textarea"
            />
            <InputError :message="form.errors.wishes" />
        </div>
        <div class="grid gap-2">
            <p class="field-label">{{ copy.colors }}</p>
            <p class="field-hint">{{ copy.colorsHint }}</p>
            <BriefColors v-model="form.colors" />
            <InputError :message="form.errors.colors" />
        </div>
        <div class="grid gap-2">
            <label for="mood" class="field-label">{{ copy.mood }}</label>
            <Input
                id="mood"
                v-model="form.mood"
                :placeholder="copy.moodPlaceholder"
            />
        </div>
        <div class="grid gap-2">
            <label for="links" class="field-label">{{ copy.links }}</label>
            <p class="field-hint">{{ copy.linksHint }}</p>
            <textarea
                id="links"
                v-model="form.links"
                rows="3"
                class="textarea"
            />
        </div>
        <div class="grid max-w-xs gap-2">
            <label for="ready_by" class="field-label">{{ copy.readyBy }}</label>
            <DatePicker id="ready_by" v-model="form.ready_by" :min="tomorrow" />
            <InputError :message="form.errors.ready_by" />
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <SaveButton
                :processing="form.processing"
                :dirty="form.isDirty"
                :saved="form.recentlySuccessful"
                :label="copy.save"
                :saved-label="copy.saved"
            />
            <Button
                v-if="owned && brief.status === 'draft'"
                type="button"
                size="pill"
                variant="outline"
                @click="send"
            >
                {{ copy.submit }}
            </Button>
        </div>
        <p v-if="submitError" class="text-sm text-destructive">
            {{ submitError }}
        </p>
        <p v-else-if="owned && brief.status === 'draft'" class="field-hint">
            {{ copy.submitHint }}
        </p>
    </form>
</template>
