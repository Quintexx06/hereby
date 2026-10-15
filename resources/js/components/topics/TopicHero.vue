<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SiteHeader from '@/components/marketing/SiteHeader.vue';
import { Button } from '@/components/ui/button';
import { topics as copy } from '@/content/landing';
import { home, register } from '@/routes';

defineProps<{ label: string; title: string; lede: string }>();
</script>

<!-- A topic page opens on night: where you are, the promise, one action. -->
<template>
    <div class="legal-band stage">
        <SiteHeader />
        <div class="page-container topic-hero">
            <nav aria-label="Brotkrumen" class="caption">
                <Link :href="home()" class="link-underline">Hereby</Link>
                <span aria-hidden="true"> / </span>
                <span>{{ label }}</span>
            </nav>
            <h1 class="display max-w-[18ch] text-balance">{{ title }}</h1>
            <p class="topic-lede">{{ lede }}</p>
            <div class="flex flex-wrap items-center gap-6">
                <Button as-child size="pill">
                    <Link :href="register()">{{ copy.cta }}</Link>
                </Button>
                <Link :href="`${home().url}#studio`" class="link-underline">
                    {{ copy.examples }}
                </Link>
            </div>
        </div>
    </div>
</template>
