<script setup lang="ts">
import LegalDocument from '@/components/marketing/LegalDocument.vue';
import { imprint } from '@/content/legal';
</script>

<template>
    <LegalDocument :page="imprint" />
</template>
