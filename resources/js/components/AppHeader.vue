<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import HeaderDesktopNav from '@/components/header/HeaderDesktopNav.vue';
import HeaderExternalLinks from '@/components/header/HeaderExternalLinks.vue';
import HeaderMobileNav from '@/components/header/HeaderMobileNav.vue';
import HeaderUserMenu from '@/components/header/HeaderUserMenu.vue';
import { Button } from '@/components/ui/button';
import { mainNavItems, secondaryNavItems } from '@/config/navigation';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <div>
        <div class="border-b border-sidebar-border/80">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <div class="lg:hidden">
                    <HeaderMobileNav
                        :items="mainNavItems"
                        :external-items="secondaryNavItems"
                    />
                </div>

                <Link :href="dashboard()" class="flex items-center gap-x-2">
                    <AppLogo />
                </Link>

                <div class="hidden h-full lg:flex lg:flex-1">
                    <HeaderDesktopNav :items="mainNavItems" />
                </div>

                <div class="ml-auto flex items-center gap-2">
                    <Button variant="ghost" size="icon" class="group size-9">
                        <Search
                            class="size-5 opacity-80 group-hover:opacity-100"
                        />
                    </Button>
                    <div class="hidden gap-1 lg:flex">
                        <HeaderExternalLinks :items="secondaryNavItems" />
                    </div>
                    <HeaderUserMenu />
                </div>
            </div>
        </div>

        <div
            v-if="breadcrumbs.length > 1"
            class="flex w-full border-b border-sidebar-border/70"
        >
            <div
                class="mx-auto flex h-12 w-full items-center justify-start px-4 text-muted-foreground md:max-w-7xl"
            >
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>
