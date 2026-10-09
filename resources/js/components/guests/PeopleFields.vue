<script setup lang="ts">
import { Plus, X } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { manualCopy } from '@/content/guests';
import type { EditableGuest } from '@/types';

/* The people of one household: shared by "add by hand" and the editor. */
defineProps<{ idPrefix: string; error?: string }>();
const guests = defineModel<EditableGuest[]>('guests', { required: true });

function addPerson(): void {
    guests.value.push({
        first_name: '',
        last_name: guests.value[0]?.last_name ?? '',
        is_child: false,
    });
}
</script>

<template>
    <div class="flex flex-col gap-3">
        <div
            v-for="(guest, index) in guests"
            :key="guest.id ?? `new-${index}`"
            class="person-fields"
        >
            <div class="field">
                <label
                    :for="`${idPrefix}_first_${index}`"
                    class="field-label"
                    :class="{ 'sr-only': index > 0 }"
                    >{{ manualCopy.firstName }}</label
                >
                <Input
                    :id="`${idPrefix}_first_${index}`"
                    v-model="guest.first_name"
                    required
                />
            </div>
            <div class="field">
                <label
                    :for="`${idPrefix}_last_${index}`"
                    class="field-label"
                    :class="{ 'sr-only': index > 0 }"
                    >{{ manualCopy.lastName }}</label
                >
                <Input
                    :id="`${idPrefix}_last_${index}`"
                    v-model="guest.last_name"
                />
            </div>
            <div class="flex h-11 items-center gap-3">
                <label class="check-label">
                    <input
                        v-model="guest.is_child"
                        type="checkbox"
                        class="checkbox"
                    />
                    {{ manualCopy.child }}
                </label>
                <button
                    v-if="guests.length > 1"
                    type="button"
                    class="icon-button"
                    :aria-label="manualCopy.removePerson"
                    @click="guests.splice(index, 1)"
                >
                    <X class="size-4" />
                </button>
            </div>
        </div>
        <button
            type="button"
            class="link-underline inline-flex items-center gap-1.5 self-start text-sm"
            @click="addPerson"
        >
            <Plus class="size-4" /> {{ manualCopy.addPerson }}
        </button>
        <InputError :message="error" />
    </div>
</template>
