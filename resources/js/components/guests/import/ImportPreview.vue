<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { guestsPage, importCopy } from '@/content/guests';
import type { ImportHousehold } from '@/types';

defineProps<{
    households: ImportHousehold[];
    selected: Set<number>;
    saving: boolean;
}>();

defineEmits<{ toggle: [number]; save: []; reset: [] }>();
</script>

<!-- Nothing is saved yet: the couple checks every household first. -->
<template>
    <div class="flex flex-col gap-4">
        <div>
            <p class="font-semibold">
                {{ importCopy.previewTitle(households.length) }}
            </p>
            <p class="field-hint">
                {{
                    households.length
                        ? importCopy.previewHint
                        : importCopy.nothingFound
                }}
            </p>
        </div>

        <ul
            v-if="households.length"
            class="max-h-[50vh] overflow-auto border-b"
        >
            <li v-for="(household, index) in households" :key="index">
                <label class="preview-row">
                    <input
                        type="checkbox"
                        class="mt-1 size-4 accent-foreground"
                        :checked="selected.has(index)"
                        @change="$emit('toggle', index)"
                    />
                    <span class="flex min-w-0 flex-col gap-0.5">
                        <span class="font-medium">{{ household.name }}</span>
                        <span
                            class="flex flex-wrap gap-x-2 gap-y-1 text-sm text-muted-foreground"
                        >
                            <span
                                v-for="guest in household.guests"
                                :key="guest.first_name + guest.last_name"
                                class="inline-flex items-center gap-1.5"
                            >
                                {{
                                    [guest.first_name, guest.last_name]
                                        .filter(Boolean)
                                        .join(' ')
                                }}
                                <span v-if="guest.is_child" class="tag">{{
                                    guestsPage.child
                                }}</span>
                                <span
                                    v-if="guest.duplicate"
                                    class="tag text-foreground"
                                    >{{ importCopy.duplicate }}</span
                                >
                            </span>
                        </span>
                        <span
                            v-if="household.email"
                            class="text-xs text-muted-foreground"
                            >{{ household.email }}</span
                        >
                    </span>
                </label>
            </li>
        </ul>

        <div class="flex flex-wrap items-center gap-4">
            <Button
                size="pill"
                :disabled="saving || selected.size === 0"
                @click="$emit('save')"
            >
                <Spinner v-if="saving" />
                {{ importCopy.importCta(selected.size) }}
            </Button>
            <button
                type="button"
                class="link-underline text-sm"
                @click="$emit('reset')"
            >
                {{ importCopy.startOver }}
            </button>
        </div>
    </div>
</template>
