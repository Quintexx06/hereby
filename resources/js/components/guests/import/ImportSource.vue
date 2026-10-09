<script setup lang="ts">
import { FileUp } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { importCopy } from '@/content/guests';

defineProps<{
    mode: 'paste' | 'file';
    reading: boolean;
    error: string | null;
}>();
const emit = defineEmits<{ read: [{ text?: string; file?: File }] }>();

const text = ref('');
const dragging = ref(false);

function onFile(file: File | undefined): void {
    if (file) {
        emit('read', { file });
    }
}
</script>

<template>
    <form
        v-if="mode === 'paste'"
        class="flex flex-col gap-3"
        @submit.prevent="emit('read', { text })"
    >
        <label for="import_text" class="field-label">{{
            importCopy.pasteLabel
        }}</label>
        <p class="field-hint">{{ importCopy.pasteHint }}</p>
        <textarea
            id="import_text"
            v-model="text"
            class="textarea"
            :placeholder="importCopy.pastePlaceholder"
        />
        <p v-if="error" class="text-sm text-destructive" role="alert">
            {{ error }}
        </p>
        <Button
            type="submit"
            size="pill"
            class="self-start"
            :disabled="reading || !text.trim()"
        >
            <Spinner v-if="reading" />
            {{ reading ? importCopy.reading : importCopy.read }}
        </Button>
    </form>

    <div v-else class="flex flex-col gap-3">
        <label
            class="dropzone"
            :data-dragging="dragging"
            @dragover.prevent="dragging = true"
            @dragleave="dragging = false"
            @drop.prevent="
                dragging = false;
                onFile($event.dataTransfer?.files[0]);
            "
        >
            <input
                type="file"
                class="sr-only"
                accept=".xlsx,.csv,.tsv,.txt,.vcf"
                @change="onFile(($event.target as HTMLInputElement).files?.[0])"
            />
            <FileUp class="size-6 text-muted-foreground" aria-hidden="true" />
            <span class="font-medium">{{
                reading ? importCopy.reading : importCopy.fileLabel
            }}</span>
            <span class="max-w-sm text-sm text-muted-foreground">{{
                importCopy.fileHint
            }}</span>
        </label>
        <p v-if="error" class="text-sm text-destructive" role="alert">
            {{ error }}
        </p>
    </div>
</template>
