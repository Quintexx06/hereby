<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { pricing } from '@/content/landing';
import { chf, plans } from '@/content/publish';
import { register } from '@/routes';

defineProps<{ price: number }>();
</script>

<!--
    The smallest package first and large, on the night stage: what the
    website alone costs, what it holds, and that building it costs nothing.
    A phone with the first design rises from the panel's lower edge.
-->
<template>
    <article class="pricing-lead stage">
        <div class="pricing-lead-body">
            <h3 class="title">{{ pricing.lead.title }}</h3>
            <p class="pricing-lead-figure">
                <span class="whitespace-nowrap">{{ chf(price) }}</span>
                <span class="pricing-lead-once">{{ pricing.once }}</span>
            </p>
            <p class="max-w-[46ch] text-muted-foreground">
                {{ pricing.lead.line }}
            </p>
            <ul class="pricing-lead-features">
                <li
                    v-for="feature in plans.signature.features"
                    :key="feature.text"
                    class="flex gap-2.5"
                >
                    <Check
                        class="mt-1 size-4 shrink-0 text-brand"
                        aria-hidden="true"
                    />
                    {{ feature.text }}
                </li>
            </ul>
            <div class="pricing-lead-start">
                <Button as-child size="pill">
                    <Link :href="register()">{{ pricing.start }}</Link>
                </Button>
                <p class="caption max-w-[34ch]">{{ pricing.lead.free }}</p>
            </div>
        </div>
        <figure class="pricing-lead-phone">
            <img
                src="/images/designs/garten-phone.webp"
                :alt="pricing.lead.shot"
                width="390"
                height="844"
                loading="lazy"
                decoding="async"
            />
        </figure>
    </article>
</template>
