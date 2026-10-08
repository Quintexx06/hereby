<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthStageLayout.vue';

const {
    title = '',
    description = '',
    mirrored = false,
} = defineProps<{
    title?: string;
    description?: string;
    /** Photo on the right, form on the left (sign-up), so it differs from sign-in. */
    mirrored?: boolean;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description" :mirrored="mirrored">
        <slot />
    </AuthLayout>
</template>
