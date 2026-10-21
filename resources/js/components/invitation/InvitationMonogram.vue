<script setup lang="ts">
import { computed } from 'vue';

/* «A & L»: the couple's initials in the template's serif. */
const props = defineProps<{ couple: string }>();

const initials = computed(() =>
    props.couple
        .split(/\s+&\s+/)
        .map((name) => name.trim().charAt(0).toUpperCase())
        .filter(Boolean),
);
</script>

<template>
    <span class="inv-monogram" aria-hidden="true">
        <template v-for="(initial, index) in initials" :key="index">
            <span v-if="index > 0" class="inv-monogram-amp">&amp;</span
            >{{ initial }}
        </template>
    </span>
</template>
