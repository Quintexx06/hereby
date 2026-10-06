<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

type Props = {
    letter?: string;
    stamp?: boolean;
    class?: HTMLAttributes['class'];
};

const { letter = 'H', stamp = false, class: className } = defineProps<Props>();
</script>

<template>
    <span
        aria-hidden="true"
        :class="
            cn(
                'seal size-16 -rotate-4 text-3xl',
                stamp && 'animate-stamp',
                className,
            )
        "
    >
        {{ letter }}
    </span>
</template>
