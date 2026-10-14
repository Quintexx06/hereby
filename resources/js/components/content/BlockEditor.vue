<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import BlockTextFields from '@/components/content/BlockTextFields.vue';
import BlockVisibilityField from '@/components/content/BlockVisibilityField.vue';
import LanguageTabs from '@/components/content/LanguageTabs.vue';
import MoveButtons from '@/components/content/MoveButtons.vue';
import ConfirmButton from '@/components/guests/editor/ConfirmButton.vue';
import InputError from '@/components/InputError.vue';
import SaveButton from '@/components/SaveButton.vue';
import { blockTypes, contentPage as copy } from '@/content/content';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
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
const emit = defineEmits<{ move: [direction: -1 | 1]; saved: [] }>();

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

useUnsavedChanges(() => form.isDirty, copy.leaveUnsaved);

/* Also on Enter in the title, and on Ctrl/Cmd+S anywhere in the block. */
function save(): void {
    if (!form.isDirty || form.processing) {
        return;
    }

    form.put(update.url([...route()]), {
        preserveScroll: true,
        onSuccess: () => {
            form.defaults();
            emit('saved');
        },
    });
}
</script>

<template>
    <form
        class="block-editor"
        :aria-labelledby="`block-${block.id}`"
        @submit.prevent="save"
        @keydown.ctrl.s.prevent="save"
        @keydown.meta.s.prevent="save"
    >
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
                @move="(direction) => emit('move', direction)"
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

        <BlockVisibilityField
            :id="`block-${block.id}-event`"
            v-model="form.event_id"
            :events="events"
            :error="form.errors.event_id"
        />

        <footer class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <SaveButton
                    :processing="form.processing"
                    :dirty="form.isDirty"
                    :saved="form.recentlySuccessful"
                    :label="copy.save"
                    :saved-label="copy.saved"
                />
                <p v-if="form.isDirty" class="field-hint" role="status">
                    {{ copy.unsaved }}
                </p>
            </div>
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
    </form>
</template>
