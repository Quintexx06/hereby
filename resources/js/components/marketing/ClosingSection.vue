<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { closing } from '@/content/landing';
import { register } from '@/routes';
</script>

<template>
    <section class="page-container section">
        <div
            v-reveal
            class="surface paper-grain flex reveal flex-col items-start gap-6 p-8 sm:p-14"
        >
            <h2 class="display-lg max-w-3xl">{{ closing.title }}</h2>
            <p class="lede">{{ closing.lede }}</p>
            <Button size="lg" as-child>
                <Link :href="register()">{{ closing.cta }}</Link>
            </Button>
        </div>
    </section>
</template>
