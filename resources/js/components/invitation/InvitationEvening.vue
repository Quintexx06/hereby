<script setup lang="ts">
import EveningTables from '@/components/invitation/EveningTables.vue';
import EveningWayHome from '@/components/invitation/EveningWayHome.vue';
import type { Evening } from '@/types';

defineProps<{ evening: Evening | null }>();
</script>

<!-- «Der Abend» on the personal link (Atelier and up); nothing otherwise. -->
<template>
    <template v-if="evening">
        <EveningTables v-if="evening.tables.length" :tables="evening.tables" />
        <EveningWayHome
            v-if="evening.wayHome.length"
            :connections="evening.wayHome"
        />
    </template>
</template>
