<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

/* A link the couple shares, read-only, with a button to copy it. */
const props = defineProps<{
    id: string;
    link: string;
    label: string;
    hint?: string;
}>();
const copied = ref(false);

async function copyLink(): Promise<void> {
    try {
        await navigator.clipboard.writeText(props.link);
        copied.value = true;
        window.setTimeout(() => (copied.value = false), 2000);
    } catch {
        /* No clipboard access: the link stays selectable in the field. */
    }
}
</script>

<template>
    <div class="field max-w-3xl">
        <label :for="id" class="field-label">{{ label }}</label>
        <div class="flex flex-wrap items-center gap-3">
            <input
                :id="id"
                class="memories-link"
                :value="link"
                readonly
                @focus="($event.target as HTMLInputElement).select()"
            />
            <Button type="button" size="pill" @click="copyLink">
                <span aria-live="polite">{{
                    copied ? 'Kopiert' : 'Link kopieren'
                }}</span>
            </Button>
        </div>
        <p v-if="hint" class="field-hint">{{ hint }}</p>
    </div>
</template>
