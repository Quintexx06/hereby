<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import HerebyWordmark from '@/components/brand/HerebyWordmark.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { dashboard } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<!-- Before the website exists there is nothing to navigate: just the mark and the account. -->
<template>
    <header class="app-focus-bar">
        <Link :href="dashboard()" aria-label="Hereby, Übersicht">
            <HerebyWordmark />
        </Link>
        <DropdownMenu v-if="user">
            <DropdownMenuTrigger class="app-focus-user">
                <UserInfo :user="user" />
                <span class="hidden text-sm font-medium sm:inline">{{
                    user.name
                }}</span>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="min-w-56" align="end">
                <UserMenuContent :user="user" />
            </DropdownMenuContent>
        </DropdownMenu>
    </header>
</template>
