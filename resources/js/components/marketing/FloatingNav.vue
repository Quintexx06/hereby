<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, nextTick, ref, watch } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import { useActiveSection } from '@/composables/useActiveSection';
import { hero, nav } from '@/content/landing';
import { home, register } from '@/routes';

const ids = nav.map((item) => item.href.replace('/#', ''));
const { active, pastHero } = useActiveSection(ids);

/** A soft pill glides behind the link of the section you are in. */
const links = ref<HTMLAnchorElement[]>([]);
const indicator = ref({ left: 0, width: 0, visible: false });
const current = computed(() =>
    nav.find((item) => item.href === `/#${active.value}`),
);

watch(
    active,
    async () => {
        await nextTick();
        const link = links.value[ids.indexOf(active.value ?? '')];
        indicator.value = link
            ? { left: link.offsetLeft, width: link.offsetWidth, visible: true }
            : { ...indicator.value, visible: false };
    },
    { immediate: true },
);
</script>

<!-- Appears once the hero is behind you: a floating pill with the page's chapters. -->
<template>
    <nav
        class="floating-nav"
        :data-visible="pastHero"
        aria-label="Seitennavigation"
    >
        <Link
            :href="home()"
            class="floating-nav-home"
            aria-label="Hereby, zur Startseite"
        >
            <AppLogoIcon class="size-6" />
        </Link>

        <div class="relative hidden items-center md:flex">
            <span
                class="floating-nav-indicator"
                :data-visible="indicator.visible"
                :style="{
                    transform: `translateX(${indicator.left}px)`,
                    width: `${indicator.width}px`,
                }"
            />
            <a
                v-for="item in nav"
                :key="item.href"
                ref="links"
                :href="item.href"
                class="floating-nav-link"
                :aria-current="item === current ? 'location' : undefined"
            >
                {{ item.label }}
            </a>
        </div>

        <span class="floating-nav-current md:hidden">{{ current?.label }}</span>

        <Button as-child size="sm" class="rounded-full px-4">
            <Link :href="register()">{{ hero.primaryCta }}</Link>
        </Button>
    </nav>
</template>
