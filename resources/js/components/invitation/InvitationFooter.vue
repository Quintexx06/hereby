<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';

const { t } = useTrans();
</script>

<template>
    <footer class="mt-auto flex flex-col items-center gap-6 pt-16 text-center">
        <hr class="rule" />
        <p class="caption">{{ t('common.credit') }}</p>
    </footer>
</template>
