<script setup lang="ts">
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import { premium } from '@/content/landing';
</script>

<!--
    The paid features, on night: what Atelier and Couture add to the day
    itself. Cards rise in one after another; each icon draws itself in.
-->
<template>
    <section id="premium" class="stage">
        <div class="page-container section flex flex-col gap-14">
            <div v-reveal class="flex max-w-4xl reveal flex-col gap-6">
                <h2 class="display">
                    {{ premium.titleLead }}
                    <span class="accent">{{ premium.titleAccent }}</span>
                </h2>
                <p class="lede">{{ premium.lede }}</p>
            </div>
            <ul class="premium-grid">
                <li
                    v-for="(item, index) in premium.items"
                    :key="item.name"
                    v-reveal
                    class="premium-card reveal"
                    :style="{ '--reveal-delay': `${(index % 4) * 70}ms` }"
                >
                    <div class="flex items-start justify-between gap-4">
                        <HerebyIcon :name="item.icon" class="size-10" />
                        <span class="premium-tier" :data-tier="item.tier">
                            {{ item.tier }}
                        </span>
                    </div>
                    <h3 class="premium-name">{{ item.name }}</h3>
                    <p class="text-sm leading-relaxed text-muted-foreground">
                        {{ item.line }}
                    </p>
                </li>
            </ul>
            <p class="caption">{{ premium.note }}</p>
        </div>
    </section>
</template>
