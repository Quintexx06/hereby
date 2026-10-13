<script setup lang="ts">
import { X } from '@lucide/vue';
import { groupPhotosPage as copy } from '@/content/evening';

export type GroupPhotoDraft = {
    id: number | null;
    title: string;
    place: string;
    household_ids: number[];
};

defineProps<{
    households: { id: number; name: string }[];
    error?: string;
}>();
defineEmits<{ remove: [] }>();
const group = defineModel<GroupPhotoDraft>({ required: true });
</script>

<!-- One group photo: its name, where it is taken, and who belongs in it. -->
<template>
    <div class="way-home-row md:grid-cols-[minmax(0,1fr)_minmax(0,14rem)_auto]">
        <label class="flex flex-col gap-1.5">
            <span class="text-sm font-medium">{{ copy.title_ }}</span>
            <input
                v-model="group.title"
                :placeholder="copy.titlePlaceholder"
                maxlength="80"
                class="seating-input"
            />
        </label>
        <label class="flex flex-col gap-1.5">
            <span class="text-sm font-medium">{{ copy.place }}</span>
            <input
                v-model="group.place"
                :placeholder="copy.placePlaceholder"
                maxlength="80"
                class="seating-input"
            />
        </label>
        <button
            type="button"
            class="way-home-remove"
            :aria-label="copy.remove"
            @click="$emit('remove')"
        >
            <X class="size-4" />
        </button>
        <details class="group-photo-who md:col-span-3">
            <summary class="cursor-pointer text-sm font-medium">
                {{ copy.who(group.household_ids.length) }}
            </summary>
            <div class="group-photo-households">
                <label
                    v-for="household in households"
                    :key="household.id"
                    class="flex items-center gap-2 text-sm"
                >
                    <input
                        v-model="group.household_ids"
                        type="checkbox"
                        :value="household.id"
                        class="size-4 accent-foreground"
                    />
                    {{ household.name }}
                </label>
            </div>
        </details>
        <p v-if="error" class="text-sm text-destructive md:col-span-3">
            {{ error }}
        </p>
    </div>
</template>
