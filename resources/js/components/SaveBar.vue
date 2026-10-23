<script setup lang="ts">
import SaveButton from '@/components/SaveButton.vue';

/* The sticky save bar of a settings page; `status` says what is pending. */
defineProps<{
    processing: boolean;
    dirty: boolean;
    saved: boolean;
    label: string;
    savedLabel: string;
    status?: string | null;
}>();
</script>

<template>
    <div class="settings-savebar">
        <p
            v-if="status"
            class="settings-savebar-status"
            :data-dirty="dirty"
            aria-live="polite"
        >
            {{ status }}
        </p>
        <SaveButton
            :processing="processing"
            :dirty="dirty"
            :saved="saved"
            :label="label"
            :saved-label="savedLabel"
        />
    </div>
</template>
