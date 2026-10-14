<script setup lang="ts">
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import { premium } from '@/content/landing';

const extras = premium.extras;
</script>

<!--
    The extras as four cards. Each large icon draws itself in as its card
    rises, the blush dot lands last; hover lifts and tilts the icon a little.
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
            >
                <div class="premium-extra-stage">
                    <HerebyIcon :name="item.icon" class="premium-extra-icon" />
                </div>
                <div class="flex flex-col gap-2">
                    <p class="premium-moment-name">{{ item.name }}</p>
                    <p class="premium-moment-line">{{ item.line }}</p>
                </div>
                <p class="premium-extra-tag" :data-couture="item.couture">
                    {{ item.couture ? extras.included : extras.add }}
                </p>
            </li>
        </ul>
    </div>
</template>
