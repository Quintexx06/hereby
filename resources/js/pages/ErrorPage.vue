<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    status: number;
    title: string;
    body: string;
    home: string;
    /** Offered where trying again can help (419, 429, 500, 503). */
    reload: string | null;
}>();

const reloadPage = (): void => window.location.reload();
</script>

<!--
    The error pages (views/errors) for a click inside the app: the same
    night page, as an Inertia page, so its links work. Plain links on purpose:
    a fresh load is the surest way out of whatever went wrong.
-->
<template>
    <Head :title="title" />

    <main class="error-page stage">
        <div class="error-page-inner">
            <a href="/" class="error-page-mark">hereby<span>.</span></a>
            <p class="error-page-code">{{ status }}</p>
            <h1 class="error-page-title">{{ title }}</h1>
            <p class="error-page-body">{{ body }}</p>
            <div class="error-page-actions">
                <a href="/" class="error-page-home">{{ home }}</a>
                <button
                    v-if="reload"
                    type="button"
                    class="error-page-reload"
                    @click="reloadPage"
                >
                    {{ reload }}
                </button>
            </div>
        </div>
    </main>
</template>
