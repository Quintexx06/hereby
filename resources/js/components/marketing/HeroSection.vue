<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import SignatureStroke from '@/components/brand/SignatureStroke.vue';
import WaxSeal from '@/components/brand/WaxSeal.vue';
import { Button } from '@/components/ui/button';
import { useGsap } from '@/composables/motion/useGsap';
import { hero } from '@/content/landing';
import { duration, stagger } from '@/lib/motion';
import { gsap, SplitText } from '@/lib/gsap';
import { login, register } from '@/routes';

const root = ref<HTMLElement | null>(null);

useGsap(root, ({ reduced }) => {
    if (reduced) {
        return;
    }

    const words = SplitText.create('[data-hero-title]', {
        type: 'words',
        mask: 'words',
    });

    gsap.timeline()
        .from(words.words, {
            yPercent: 110,
            duration: duration.hero,
            stagger: stagger.base,
        })
        .from(
            '[data-hero-fade]',
            { autoAlpha: 0, y: 12, stagger: stagger.loose },
            '-=0.6',
        )
        .from(
            '[data-signature]',
            { drawSVG: '0%', duration: 1.4, ease: 'hereby.inOut' },
            '-=0.4',
        )
        .from(
            '[data-hero-seal]',
            { autoAlpha: 0, scale: 1.15, rotate: -14, duration: 0.42 },
            '-=0.5',
        );
});
</script>

<template>
    <section ref="root" class="page-container section relative">
        <p data-hero-fade class="eyebrow mb-8">{{ hero.eyebrow }}</p>

        <h1 data-hero-title class="display-xl max-w-4xl">
            {{ hero.titleLead }}
            <em class="display-italic text-seal">{{ hero.titleAccent }}</em>
        </h1>

        <div
            class="mt-10 flex flex-col gap-10 md:flex-row md:items-end md:justify-between"
        >
            <div class="flex flex-col gap-8">
                <p data-hero-fade class="lede">{{ hero.lede }}</p>
                <div data-hero-fade class="flex flex-wrap items-center gap-3">
                    <Button size="lg" as-child>
                        <Link :href="register()">{{ hero.primaryCta }}</Link>
                    </Button>
                    <Link :href="login()" class="link-ink text-sm font-medium">
                        {{ hero.secondaryCta }}
                    </Link>
                </div>
            </div>

            <div class="relative w-full max-w-sm shrink-0 text-foreground">
                <SignatureStroke class="w-full" />
                <hr class="rule mt-1" />
                <p class="fine-print mt-2">Signature of declarant</p>
                <WaxSeal data-hero-seal class="absolute -top-6 right-0" />
            </div>
        </div>
    </section>
</template>
