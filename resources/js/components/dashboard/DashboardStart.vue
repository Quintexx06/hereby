<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import { onMounted } from 'vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { start } from '@/content/dashboard';
import { landingPhotos } from '@/content/landing-photos';
import { store } from '@/routes/weddings';

/* The setup's first page loads while they read, so the click answers at once. */
onMounted(() => void import('@/pages/setup/Step.vue'));
</script>

<!--
    First visit: one invitation to start, what the setup will do, and a
    photo beside it on wide screens. The button says it is working the
    moment it is pressed.
-->
<template>
    <section class="dashboard-start">
        <div class="flex max-w-xl flex-col gap-8">
            <div class="flex flex-col gap-4">
                <h1 class="masthead-title">{{ start.title }}</h1>
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
                <Button
                    type="submit"
                    size="pill"
                    class="start-cta"
                    :disabled="processing"
                >
                    <Spinner v-if="processing" />
                    {{ processing ? start.starting : start.cta }}
                    <ArrowRight
                        v-if="!processing"
                        class="size-4"
                        aria-hidden="true"
                    />
                </Button>
            </Form>
        </div>
        <figure class="dashboard-start-photo">
            <ResponsivePhoto
                :photo="landingPhotos.bouquet"
                sizes="(min-width: 1024px) 28rem, 1px"
                class="size-full object-cover"
            />
        </figure>
    </section>
</template>
