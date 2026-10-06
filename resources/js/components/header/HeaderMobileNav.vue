<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Menu } from '@lucide/vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
    externalItems: NavItem[];
}>();

const { whenCurrentUrl } = useCurrentUrl();
</script>

<template>
    <Sheet>
        <SheetTrigger :as-child="true">
            <Button variant="ghost" size="icon" class="mr-2 size-9">
                <Menu class="size-5" />
            </Button>
        </SheetTrigger>
        <SheetContent side="left" class="w-[300px] p-6">
            <SheetTitle class="sr-only">Navigation menu</SheetTitle>
            <SheetHeader class="flex justify-start text-left">
                <AppLogoIcon class="size-6" />
            </SheetHeader>
            <div class="flex h-full flex-1 flex-col justify-between py-6">
                <nav class="-mx-3 space-y-1">
                    <Link
                        v-for="item in items"
                        :key="item.title"
                        :href="item.href"
                        class="flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-medium hover:bg-accent"
                        :class="whenCurrentUrl(item.href, 'bg-accent')"
                    >
                        <component
                            :is="item.icon"
                            v-if="item.icon"
                            class="size-5"
                        />
                        {{ item.title }}
                    </Link>
                </nav>
                <div class="flex flex-col gap-4">
                    <a
                        v-for="item in externalItems"
                        :key="item.title"
                        :href="toUrl(item.href)"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center gap-2 text-sm font-medium"
                    >
                        <component
                            :is="item.icon"
                            v-if="item.icon"
                            class="size-5"
                        />
                        <span>{{ item.title }}</span>
                    </a>
                </div>
            </div>
        </SheetContent>
    </Sheet>
</template>
