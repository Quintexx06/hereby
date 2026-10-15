<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import SidebarWedding from '@/components/sidebar/SidebarWedding.vue';
import {
    adminNavItems,
    mainNavItems,
    partnerNavItems,
    secondaryNavItems,
} from '@/config/navigation';
import { dashboard } from '@/routes';

const page = usePage();
const navItems = computed(() =>
    page.props.partner
        ? partnerNavItems(page.props.partner)
        : mainNavItems(page.props.currentWedding),
);
const teamItems = computed(() =>
    page.props.adminInbox === null ? [] : adminNavItems(page.props.adminInbox),
);
</script>

<!--
    Hereby's own app nav, always night. Full, icon rail or drawer is up to
    CSS (app-nav.css), so the same markup stays mounted at every width.
-->
<template>
    <aside id="app-nav" class="app-nav stage">
        <Link :href="dashboard()" class="app-nav-logo">
            <AppLogo />
        </Link>

        <div class="app-nav-scroll">
            <SidebarWedding />
            <NavMain :items="navItems" label-sr="Hochzeit" />
            <NavMain v-if="teamItems.length" label="Team" :items="teamItems" />
        </div>

        <div class="app-nav-foot">
            <NavFooter :items="secondaryNavItems" />
            <NavUser />
        </div>
    </aside>
</template>
