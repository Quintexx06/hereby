<script setup lang="ts">
defineProps<{ label: string; hint?: string }>();
const checked = defineModel<boolean>({ required: true });
</script>

<!-- A setting as a row: what it does on the left, an on/off switch on the right. -->
<template>
    <label class="switch-row">
        <span class="flex min-w-0 flex-col gap-0.5">
            <span class="font-medium">{{ label }}</span>
            <span v-if="hint" class="text-sm text-muted-foreground">{{
                hint
            }}</span>
        </span>
        <input v-model="checked" type="checkbox" role="switch" class="switch" />
    </label>
</template>
