<script setup lang="ts">
import { Check, Copy, Link2 } from '@lucide/vue';
import { ref } from 'vue';
import { partnerCalendar as copy } from '@/content/partners';
import { connect } from '@/routes/partners';

const props = defineProps<{ code: string }>();
const copied = ref<'code' | 'link' | null>(null);

async function copyText(what: 'code' | 'link'): Promise<void> {
    const text =
        what === 'code'
            ? props.code
            : `${window.location.origin}${connect.url(props.code)}`;

    try {
        await navigator.clipboard.writeText(text);
        copied.value = what;
        setTimeout(() => (copied.value = null), 1800);
    } catch {
        copied.value = null;
    }
}
</script>

<!-- The code couples type to connect this partner, or a link that does it. -->
<template>
    <section class="partner-code-card" aria-labelledby="partner-code-label">
        <div class="flex flex-col gap-1">
            <p id="partner-code-label" class="field-label">
                {{ copy.codeLabel }}
            </p>
            <p class="partner-code-value">{{ code }}</p>
            <p class="field-hint max-w-md">{{ copy.codeHint }}</p>
        </div>
        <div class="partner-code-actions">
            <button
                type="button"
                class="witness-action gap-2"
                @click="copyText('link')"
            >
                <component
                    :is="copied === 'link' ? Check : Link2"
                    class="size-4"
                />
                {{ copied === 'link' ? copy.copied : copy.copyLink }}
            </button>
            <button
                type="button"
                class="witness-action gap-2"
                @click="copyText('code')"
            >
                <component
                    :is="copied === 'code' ? Check : Copy"
                    class="size-4"
                />
                {{ copied === 'code' ? copy.copied : copy.copy }}
            </button>
        </div>
    </section>
</template>
