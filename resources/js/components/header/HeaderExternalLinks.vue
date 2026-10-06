<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { toUrl } from '@/lib/utils';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();
</script>

<template>
    <TooltipProvider :delay-duration="0">
        <Tooltip v-for="item in items" :key="item.title">
            <TooltipTrigger as-child>
                <Button
                    variant="ghost"
                    size="icon"
                    as-child
                    class="group size-9"
                >
                    <a
                        :href="toUrl(item.href)"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span class="sr-only">{{ item.title }}</span>
                        <component
                            :is="item.icon"
                            class="size-5 opacity-80 group-hover:opacity-100"
                        />
                    </a>
                </Button>
            </TooltipTrigger>
            <TooltipContent>
                <p>{{ item.title }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
