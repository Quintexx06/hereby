<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import HerebyWordmark from '@/components/brand/HerebyWordmark.vue';
import { Button } from '@/components/ui/button';
import { dashboard, home, login, register } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <header class="page-container flex h-20 items-center justify-between">
        <Link :href="home()" aria-label="Hereby home">
            <HerebyWordmark />
        </Link>

        <nav class="flex items-center gap-1">
            <Button v-if="user" as-child>
                <Link :href="dashboard()">Dashboard</Link>
            </Button>
            <template v-else>
                <Link :href="login()" class="nav-link">Sign in</Link>
                <Button as-child>
                    <Link :href="register()">Get started</Link>
                </Button>
            </template>
        </nav>
    </header>
</template>
