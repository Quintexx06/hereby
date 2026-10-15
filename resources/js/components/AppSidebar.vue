<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import SidebarViewSwitch from '@/components/sidebar/SidebarViewSwitch.vue';
import SidebarWedding from '@/components/sidebar/SidebarWedding.vue';
import {
    adminNavItems,
    mainNavItems,
    partnerNavItems,
    secondaryNavItems,
} from '@/config/navigation';
import { dashboard } from '@/routes';

const page = usePage();
const view = computed(() => page.props.adminView);

/* In the team view the wedding nav shows only inside a couple's pages. */
const navItems = computed(() => {
    if (page.props.partner) {
        return partnerNavItems(page.props.partner);
    }

    if (view.value === 'team' && !page.props.currentWedding) {
        return [];
    }

    return mainNavItems(page.props.currentWedding);
});
const teamItems = computed(() =>
    view.value === 'team'
        ? adminNavItems(page.props.adminInbox ?? 0, page.props.adminIssues ?? 0)
        : [],
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
            <SidebarViewSwitch v-if="view" :view="view" />
            <template v-if="view === 'team'">
                <NavMain label="Team" :items="teamItems" />
                <template v-if="navItems.length">
                    <SidebarWedding />
                    <NavMain :items="navItems" label-sr="Hochzeit" />
                </template>
            </template>
            <template v-else>
                <SidebarWedding />
                <NavMain :items="navItems" label-sr="Hochzeit" />
            </template>
        </div>

        <div class="app-nav-foot">
            <NavFooter :items="secondaryNavItems" />
            <NavUser />
        </div>
    </aside>
</template>
