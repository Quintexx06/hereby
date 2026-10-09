<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { start } from '@/content/dashboard';
import { store } from '@/routes/weddings';
</script>

<!-- First visit: one invitation to start, and what the setup will do. -->
<template>
    <section class="flex max-w-2xl flex-col gap-8">
        <div class="flex flex-col gap-4">
            <h1 class="app-title">{{ start.title }}</h1>
            <p class="lede">{{ start.lede }}</p>
        </div>
        <ol>
            <li
                v-for="(line, index) in start.steps"
                :key="line"
                class="start-step"
            >
                <span class="font-semibold text-foreground tabular-nums">{{
                    index + 1
                }}</span>
                <span>{{ line }}</span>
            </li>
        </ol>
        <Form v-bind="store.form()" v-slot="{ processing }">
            <Button type="submit" size="pill" :disabled="processing">
                <Spinner v-if="processing" />
                {{ start.cta }}
            </Button>
        </Form>
    </section>
</template>
