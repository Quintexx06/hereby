<script setup lang="ts">
import { ChevronDown } from '@lucide/vue';
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock }>();
</script>

<!-- Native details: keyboard and screen readers for free, no script. -->
<template>
    <div class="flex flex-col">
        <details
            v-for="(item, index) in block.items"
            :key="index"
            class="block-faq group"
        >
            <summary class="block-faq-question">
                {{ item.question }}
                <ChevronDown class="block-faq-chevron" aria-hidden="true" />
            </summary>
            <p class="pb-5 text-muted-foreground">{{ item.answer }}</p>
        </details>
    </div>
</template>
