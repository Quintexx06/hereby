<script setup lang="ts">
import { Plus } from '@lucide/vue';
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import { premium } from '@/content/landing';

const extras = premium.extras;
</script>

<!-- The extras as tickets: torn-off, numbered, clearly something you add. -->
<template>
    <div class="flex flex-col gap-8">
        <div
            class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
        >
            <h3 class="premium-package-title">{{ extras.title }}</h3>
            <p class="caption max-w-sm sm:text-right">{{ extras.lede }}</p>
        </div>
        <ul class="premium-tickets">
            <li
                v-for="(item, index) in extras.items"
                :key="item.name"
                v-reveal
                class="premium-ticket reveal"
                :style="{ '--reveal-delay': `${index * 70}ms` }"
            >
                <div class="premium-ticket-stub">
                    <Plus class="size-5" aria-hidden="true" />
                    <span class="premium-ticket-number">0{{ index + 1 }}</span>
                </div>
                <div class="flex min-w-0 flex-col gap-3 p-6">
                    <div class="flex items-center justify-between gap-3">
                        <span class="premium-tag">{{ extras.add }}</span>
                        <HerebyIcon :name="item.icon" class="size-8" />
                    </div>
                    <p class="premium-moment-name">{{ item.name }}</p>
                    <p class="premium-moment-line">{{ item.line }}</p>
                    <p v-if="item.couture" class="premium-included">
                        {{ extras.included }}
                    </p>
                </div>
            </li>
        </ul>
    </div>
</template>
