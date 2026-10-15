<script setup lang="ts">
import { Check, Copy } from '@lucide/vue';
import { ref } from 'vue';
import { partnerCalendar as copy } from '@/content/partners';

const props = defineProps<{ code: string }>();
const copied = ref(false);

async function copyCode(): Promise<void> {
    try {
        await navigator.clipboard.writeText(props.code);
        copied.value = true;
        setTimeout(() => (copied.value = false), 1800);
    } catch {
        copied.value = false;
    }
}
</script>

<!-- The code couples type to connect this partner, with a copy button. -->
<template>
    <section class="partner-code-card" aria-labelledby="partner-code-label">
        <div class="flex flex-col gap-1">
            <p id="partner-code-label" class="field-label">
                {{ copy.codeLabel }}
            </p>
            <p class="partner-code-value">{{ code }}</p>
            <p class="field-hint max-w-md">{{ copy.codeHint }}</p>
        </div>
        <button type="button" class="witness-action gap-2" @click="copyCode">
            <component :is="copied ? Check : Copy" class="size-4" />
            {{ copied ? copy.copied : copy.copy }}
        </button>
    </section>
</template>
