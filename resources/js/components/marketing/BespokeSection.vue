<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { bespokeBand } from '@/content/landing';
import { chf } from '@/content/publish';
import { register } from '@/routes';

defineProps<{ price: number }>();
</script>

<!--
    Right after the five designs, on the night stage so it reads as an offer
    of its own: the Massanfertigung, a site built to measure by hand.
-->
<template>
    <section id="massanfertigung" class="page-container pb-24 sm:pb-32">
        <article v-reveal class="bespoke-band stage reveal">
            <div class="flex flex-col gap-6">
                <h2 class="display">
                    {{ bespokeBand.titleLead }}
                    <span class="accent">{{ bespokeBand.titleAccent }}</span>
                </h2>
                <p class="lede max-w-[44ch] text-foreground/85">
                    {{ bespokeBand.lede }}
                </p>
            </div>
            <ol class="bespoke-band-steps">
                <li
                    v-for="(step, index) in bespokeBand.steps"
                    :key="step.title"
                    class="bespoke-band-step"
                >
                    <span class="bespoke-band-number">{{ index + 1 }}</span>
                    <p class="font-semibold">{{ step.title }}</p>
                    <p class="text-sm text-muted-foreground">
                        {{ step.body }}
                    </p>
                </li>
            </ol>
            <div class="bespoke-band-start">
                <Button as-child size="pill">
                    <Link :href="register()">{{ bespokeBand.cta }}</Link>
                </Button>
                <div class="flex flex-col gap-1">
                    <p class="font-medium">
                        {{ bespokeBand.price(chf(price)) }}
                    </p>
                    <p class="caption">
                        {{ bespokeBand.after }}
                        <a
                            href="mailto:info@hereby.ch"
                            class="link-underline hit-area"
                            >info@hereby.ch</a
                        >
                    </p>
                </div>
            </div>
        </article>
    </section>
</template>
