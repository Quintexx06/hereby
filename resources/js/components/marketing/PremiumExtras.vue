<script setup lang="ts">
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import { premium } from '@/content/landing';
import { chf } from '@/content/publish';
import type { LandingPrices } from '@/types/landing';

defineProps<{ prices: LandingPrices['addons'] }>();

const extras = premium.extras;

/* The card's light follows the pointer; set on the card itself, not a parent. */
function follow(event: PointerEvent): void {
    const card = event.currentTarget as HTMLElement;
    const box = card.getBoundingClientRect();
    card.style.setProperty('--x', `${event.clientX - box.left}px`);
    card.style.setProperty('--y', `${event.clientY - box.top}px`);
}
</script>

<!--
    The extras as four cards. Each large icon draws itself in as its card
    rises, the blush dot lands last. On hover a soft light follows the
    pointer, the icon lifts on a spring, and the other cards step back.
-->
<template>
    <div class="flex flex-col gap-8">
        <div
            class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
        >
            <h3 class="premium-package-title">{{ extras.title }}</h3>
            <p class="caption max-w-sm sm:text-right">{{ extras.lede }}</p>
        </div>
        <ul class="premium-extras">
            <li
                v-for="(item, index) in extras.items"
                :key="item.name"
                v-reveal="index * 90"
                class="premium-extra reveal"
                @pointermove="follow"
            >
                <div class="premium-extra-stage">
                    <HerebyIcon :name="item.icon" class="premium-extra-icon" />
                </div>
                <div class="flex flex-col gap-2">
                    <p class="premium-moment-name">{{ item.name }}</p>
                    <p class="premium-moment-line">{{ item.line }}</p>
                </div>
                <p class="premium-extra-tag" :data-couture="item.couture">
                    <span class="premium-extra-price">
                        {{ chf(prices[item.addon]) }}
                        <template v-if="item.addon === 'keep_memories'">{{
                            extras.perYear
                        }}</template>
                    </span>
                    {{ item.couture ? extras.included : extras.fromAtelier }}
                </p>
            </li>
        </ul>
    </div>
</template>
