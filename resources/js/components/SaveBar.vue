<script setup lang="ts">
import SaveButton from '@/components/SaveButton.vue';

/* The sticky save bar of a settings page. */
defineProps<{
    processing: boolean;
    dirty: boolean;
    saved: boolean;
    label: string;
    savedLabel: string;
}>();
</script>

<template>
    <div class="settings-savebar">
        <SaveButton
            :processing="processing"
            :dirty="dirty"
            :saved="saved"
            :label="label"
            :saved-label="savedLabel"
        />
    </div>
</template>
