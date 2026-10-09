<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useFooterReveal } from '@/composables/motion/useFooterReveal';
import { footer } from '@/content/landing';
import { imprint, privacy } from '@/routes/legal';

const year = new Date().getFullYear();
const root = ref<HTMLElement | null>(null);
const { isPending } = useFooterReveal(root);
</script>

<template>
    <footer ref="root" class="stage overflow-hidden" :data-pending="isPending">
        <div class="footer-mark" aria-hidden="true">
            <span class="footer-mark-word"
                ><span
                    v-for="(letter, index) in 'hereby'"
                    :key="index"
                    data-letter
                    class="footer-letter"
                    >{{ letter }}</span
                ><span data-stop class="footer-mark-stop">.</span></span
            >
        </div>

        <div class="page-container">
            <div class="caption footer-legal">
                <p>© {{ year }} Hereby. Gemacht in der Schweiz.</p>
                <nav
                    aria-label="Rechtliches"
                    class="flex flex-wrap gap-x-8 gap-y-2"
                >
                    <a
                        :href="`mailto:${footer.email}`"
                        class="link-underline"
                        >{{ footer.email }}</a
                    >
                    <Link :href="privacy()" class="link-underline">{{
                        footer.privacy
                    }}</Link>
                    <Link :href="imprint()" class="link-underline">{{
                        footer.imprint
                    }}</Link>
                </nav>
            </div>
        </div>
    </footer>
</template>
