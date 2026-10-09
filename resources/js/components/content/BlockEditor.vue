<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import BlockTextFields from '@/components/content/BlockTextFields.vue';
import LanguageTabs from '@/components/content/LanguageTabs.vue';
import MoveButtons from '@/components/content/MoveButtons.vue';
import ConfirmButton from '@/components/guests/editor/ConfirmButton.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { blockTypes, contentPage as copy } from '@/content/content';
import { eventTypes } from '@/content/setup';
import { firstError } from '@/lib/forms';
import { destroy, update } from '@/routes/weddings/content';
import type {
    BlockText,
    ContentEvent,
    ContentWedding,
    EditableBlock,
    Locale,
} from '@/types';

const props = defineProps<{
    block: EditableBlock;
    wedding: ContentWedding;
    events: ContentEvent[];
    first: boolean;
    last: boolean;
}>();
defineEmits<{ move: [direction: -1 | 1] }>();

const empty = (): BlockText => ({ title: '', body: '', items: [] });
const form = useForm({
    event_id: props.block.event_id,
    content: Object.fromEntries(
        props.wedding.languages.map((locale) => [
            locale,
            { ...empty(), ...props.block.content[locale] },
        ]),
    ) as Record<Locale, BlockText>,
});
const language = ref<Locale>(props.wedding.languages[0]);
const route = () => [props.wedding.id, props.block.id] as const;

const filled = (locale: Locale) => {
    const text = form.content[locale];

    return Boolean(
        text.body?.trim() || text.items.some((item) => item.question.trim()),
    );
};
</script>

<template>
    <article class="block-editor" :aria-labelledby="`block-${block.id}`">
        <header class="flex items-start justify-between gap-3">
            <div class="flex flex-col gap-1">
                <h2 :id="`block-${block.id}`" class="app-section-title">
                    {{ blockTypes[block.type].label }}
                </h2>
                <p class="field-hint">{{ blockTypes[block.type].hint }}</p>
            </div>
            <MoveButtons
                :first="first"
                :last="last"
                @move="(direction) => $emit('move', direction)"
            />
        </header>

        <p v-if="block.type === 'venue'" class="text-sm text-muted-foreground">
            {{
                wedding.venue
                    ? copy.venueFrom(wedding.venue)
                    : copy.venueMissing
            }}
        </p>

        <LanguageTabs
            v-model="language"
            :languages="wedding.languages"
            :filled="filled"
        />
        <p
            v-if="language !== wedding.default_locale && !filled(language)"
            class="field-hint"
        >
            {{ copy.missingLanguage }}
        </p>

        <BlockTextFields
            :key="language"
            v-model="form.content[language]"
            :type="block.type"
            :id-prefix="`block-${block.id}-${language}`"
        />
        <InputError :message="firstError(form.errors, 'content')" />

        <div class="field">
            <label :for="`block-${block.id}-event`" class="field-label">{{
                copy.visibility
            }}</label>
            <select
                :id="`block-${block.id}-event`"
                v-model="form.event_id"
                class="select-native sm:max-w-sm"
            >
                <option :value="null">{{ copy.everyone }}</option>
                <option
                    v-for="event in events"
                    :key="event.id"
                    :value="event.id"
                >
                    {{ copy.onlyEvent(event.name || eventTypes[event.type]) }}
                </option>
            </select>
            <InputError :message="form.errors.event_id" />
        </div>

        <footer class="flex flex-wrap items-center justify-between gap-4">
            <Button
                size="pill"
                :disabled="form.processing"
                @click="
                    form.put(update.url([...route()]), { preserveScroll: true })
                "
            >
                <Spinner v-if="form.processing" />
                {{ copy.save }}
            </Button>
            <ConfirmButton
                class="text-destructive"
                :label="copy.remove"
                :question="copy.removeConfirm"
                @confirm="
                    router.delete(destroy.url([...route()]), {
                        preserveScroll: true,
                    })
                "
            />
        </footer>
    </article>
</template>
