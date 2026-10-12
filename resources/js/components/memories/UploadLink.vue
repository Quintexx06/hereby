<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { memoriesPage as copy } from '@/content/memories';

/* The one link the couple shares with every guest, with a button to copy it. */
const props = defineProps<{ link: string }>();
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
        <label for="memories-link" class="field-label">{{
            copy.linkLabel
        }}</label>
        <div class="flex flex-wrap items-center gap-3">
            <input
                id="memories-link"
                class="memories-link"
                :value="link"
                readonly
                @focus="($event.target as HTMLInputElement).select()"
            />
            <Button type="button" size="pill" @click="copyLink">
                <span aria-live="polite">{{
                    copied ? copy.copied : copy.copy
                }}</span>
            </Button>
        </div>
        <p class="field-hint">{{ copy.linkHint }}</p>
    </div>
</template>
