<script setup lang="ts">
import { toUrl } from '@/lib/utils';
import type { NavItem } from '@/types';

defineProps<{ items: NavItem[] }>();
</script>

<template>
    <ul class="flex flex-col">
        <li v-for="item in items" :key="item.title">
            <a
                :href="toUrl(item.href)"
                target="_blank"
                rel="noopener noreferrer"
                class="app-nav-item app-nav-item-quiet"
            >
                <component :is="item.icon" class="app-nav-icon" />
                <span class="app-nav-label">{{ item.title }}</span>
                <span class="app-nav-tip" aria-hidden="true">{{
                    item.title
                }}</span>
            </a>
        </li>
    </ul>
</template>
