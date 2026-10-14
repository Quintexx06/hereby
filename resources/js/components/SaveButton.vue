<script setup lang="ts">
import { Check } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';

/*
 * Save, idle until there is something to save. After a save it morphs to a
 * tick and "Gespeichert" for a moment (crossfade through a slight blur).
 */
defineProps<{
    processing: boolean;
    dirty: boolean;
    saved: boolean;
    label: string;
    savedLabel: string;
}>();
</script>

<template>
    <Button
        type="submit"
        size="pill"
        :disabled="processing || (!dirty && !saved)"
    >
        <Transition name="swap" mode="out-in">
            <span
                v-if="saved"
                key="saved"
                class="inline-flex items-center gap-2"
            >
                <Check class="size-4" /> {{ savedLabel }}
            </span>
            <span v-else key="save" class="inline-flex items-center gap-2">
                <Spinner v-if="processing" />
                {{ label }}
            </span>
        </Transition>
    </Button>
</template>
