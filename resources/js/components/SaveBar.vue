<script setup lang="ts">
import { Check } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';

/*
 * The sticky save bar of a settings page. After a save the button morphs to
 * a tick and "Gespeichert" for a moment (crossfade through a slight blur).
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
    <div class="settings-savebar">
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
    </div>
</template>
