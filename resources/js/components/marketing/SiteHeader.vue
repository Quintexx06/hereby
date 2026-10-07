<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import HerebyWordmark from '@/components/brand/HerebyWordmark.vue';
import { Button } from '@/components/ui/button';
import { hero, nav } from '@/content/landing';
import { dashboard, home, login, register } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<!-- Floats over the hero; inherits the stage's light-on-dark tokens. -->
<template>
    <header class="site-header stage bg-transparent">
        <div
            class="page-container grid h-20 grid-cols-[1fr_auto] items-center gap-6 sm:h-24 lg:grid-cols-[1fr_auto_1fr]"
        >
            <Link
                :href="home()"
                aria-label="Hereby, zur Startseite"
                class="justify-self-start"
            >
                <HerebyWordmark />
            </Link>

            <nav
                aria-label="Hauptnavigation"
                class="hidden items-center lg:flex"
            >
                <a
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    class="nav-link"
                >
                    {{ item.label }}
                </a>
            </nav>

            <div class="flex items-center gap-2 justify-self-end">
                <Button v-if="user" as-child size="pill">
                    <Link :href="dashboard()">Dashboard</Link>
                </Button>
                <template v-else>
                    <Link
                        :href="login()"
                        class="nav-link hidden sm:inline-flex"
                    >
                        Anmelden
                    </Link>
                    <Button as-child size="pill">
                        <Link :href="register()">{{ hero.primaryCta }}</Link>
                    </Button>
                </template>
            </div>
        </div>
    </header>
</template>
