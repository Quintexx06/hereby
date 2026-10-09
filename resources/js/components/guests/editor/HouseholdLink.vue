<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import CopyLinkButton from '@/components/guests/CopyLinkButton.vue';
import ConfirmButton from '@/components/guests/editor/ConfirmButton.vue';
import { editCopy } from '@/content/guests';
import { destroy, renewLink } from '@/routes/weddings/households';
import type { HouseholdRow } from '@/types';

const props = defineProps<{ weddingId: number; household: HouseholdRow }>();
const emit = defineEmits<{ removed: [] }>();
const busy = ref(false);

const route = () => [props.weddingId, props.household.id] as const;
const done = { preserveScroll: true, onFinish: () => (busy.value = false) };

function renew(): void {
    busy.value = true;
    router.post(renewLink.url([...route()]), {}, done);
}

function remove(): void {
    busy.value = true;
    router.delete(destroy.url([...route()]), {
        ...done,
        onSuccess: () => emit('removed'),
    });
}
</script>

<template>
    <div class="flex flex-col gap-6">
        <section class="editor-section">
            <div class="flex flex-col gap-1">
                <p class="field-label">{{ editCopy.link }}</p>
                <p class="truncate text-sm text-muted-foreground select-all">
                    {{ household.link }}
                </p>
            </div>
            <CopyLinkButton
                class="self-start"
                :link="household.link"
                :household="household.name"
            />
            <p class="field-hint">{{ editCopy.linkHint }}</p>
            <ConfirmButton
                :label="editCopy.renew"
                :question="editCopy.renewConfirm"
                :busy="busy"
                @confirm="renew"
            />
        </section>
        <section class="editor-section pb-2">
            <ConfirmButton
                class="text-destructive"
                :label="editCopy.remove"
                :question="editCopy.removeConfirm"
                :busy="busy"
                @confirm="remove"
            />
        </section>
    </div>
</template>
