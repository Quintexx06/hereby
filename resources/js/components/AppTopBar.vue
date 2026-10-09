<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import HerebyWordmark from '@/components/brand/HerebyWordmark.vue';
import HeaderMobileNav from '@/components/header/HeaderMobileNav.vue';
import HeaderUserMenu from '@/components/header/HeaderUserMenu.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import {
    adminNavItems,
    mainNavItems,
    secondaryNavItems,
} from '@/config/navigation';
import { dashboard } from '@/routes';

const page = usePage();
const { isCurrentUrl } = useCurrentUrl();

/* Before the website exists there is nothing to navigate; the bar stays bare. */
const coupleItems = computed(() =>
    page.props.currentWedding?.status === 'active'
        ? mainNavItems(page.props.currentWedding)
        : [],
);
const teamItems = computed(() =>
    page.props.adminInbox === null ? [] : adminNavItems(page.props.adminInbox),
);
const allItems = computed(() => [...coupleItems.value, ...teamItems.value]);
</script>

<!-- The app's masthead strip: wordmark, chapters as words, the account. -->
<template>
    <header class="app-topbar">
        <div class="app-topbar-inner">
            <div v-if="allItems.length" class="lg:hidden">
                <HeaderMobileNav
                    :items="allItems"
                    :external-items="secondaryNavItems"
                />
            </div>
            <Link :href="dashboard()" aria-label="Hereby, Übersicht">
                <HerebyWordmark />
            </Link>

            <nav
                v-if="allItems.length"
                class="app-topbar-nav"
                aria-label="Hauptnavigation"
            >
                <template v-for="(group, index) in [coupleItems, teamItems]">
                    <span
                        v-if="index === 1 && group.length && coupleItems.length"
                        :key="`divider-${index}`"
                        class="app-topbar-divider"
                        aria-hidden="true"
                    />
                    <Link
                        v-for="item in group"
                        :key="item.title"
                        :href="item.href"
                        class="app-topbar-link"
                        :aria-current="
                            isCurrentUrl(item.href) ? 'page' : undefined
                        "
                    >
                        {{ item.title }}
                        <sup v-if="item.badge" class="app-topbar-badge">{{
                            item.badge
                        }}</sup>
                    </Link>
                </template>
            </nav>

            <div class="ml-auto">
                <HeaderUserMenu />
            </div>
        </div>
    </header>
</template>
