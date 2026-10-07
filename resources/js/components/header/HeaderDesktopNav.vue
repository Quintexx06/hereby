<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl, whenCurrentUrl } = useCurrentUrl();
</script>

<template>
    <NavigationMenu class="ml-10 flex h-full items-stretch">
        <NavigationMenuList class="flex h-full items-stretch gap-2">
            <NavigationMenuItem
                v-for="item in items"
                :key="item.title"
                class="relative flex h-full items-center"
            >
                <Link
                    :class="[
                        navigationMenuTriggerStyle(),
                        whenCurrentUrl(item.href, 'text-foreground'),
                        'h-9 px-3',
                    ]"
                    :href="item.href"
                >
                    <component
                        :is="item.icon"
                        v-if="item.icon"
                        class="mr-2 size-4"
                    />
                    {{ item.title }}
                </Link>
                <div
                    v-if="isCurrentUrl(item.href)"
                    class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-brand"
                />
            </NavigationMenuItem>
        </NavigationMenuList>
    </NavigationMenu>
</template>
