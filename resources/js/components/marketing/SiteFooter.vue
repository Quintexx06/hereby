<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { useFooterReveal } from '@/composables/motion/useFooterReveal';
import { footer, hero, nav } from '@/content/landing';
import { login, register } from '@/routes';
import { imprint, privacy } from '@/routes/legal';

const year = new Date().getFullYear();
const root = ref<HTMLElement | null>(null);
const { isPending } = useFooterReveal(root);
</script>

<template>
    <footer ref="root" class="stage overflow-hidden" :data-pending="isPending">
        <div class="page-container footer-column pt-20 sm:pt-28">
            <p data-tagline class="footer-tagline">{{ footer.tagline }}</p>
            <p data-item class="lede">{{ footer.lede }}</p>
            <div data-item>
                <Button as-child size="pill">
                    <Link :href="register()">{{ hero.primaryCta }}</Link>
                </Button>
            </div>
            <nav
                data-item
                aria-label="Seitennavigation"
                class="flex flex-wrap gap-x-1"
            >
                <a
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    class="nav-link pl-0"
                >
                    {{ item.label }}
                </a>
                <Link :href="login()" class="nav-link pl-0">Anmelden</Link>
            </nav>
            <a
                data-item
                :href="`mailto:${footer.email}`"
                class="link-underline lede"
                >{{ footer.email }}</a
            >
        </div>

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
                <nav aria-label="Rechtliches" class="flex gap-8">
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
