<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { adminPartners as copy } from '@/content/partners';

defineProps<{ hasLogin: boolean; canInvite: boolean; processing: boolean }>();
defineEmits<{ invite: [] }>();
</script>

<!-- The dialog's foot: send the sign-in link (or set one up), and save. -->
<template>
    <div
        class="partner-edit-wide flex flex-wrap items-center justify-between gap-4 pt-2"
    >
        <button
            v-if="canInvite"
            type="button"
            class="text-action text-sm"
            :disabled="processing"
            @click="$emit('invite')"
        >
            {{ hasLogin ? copy.resend : copy.inviteLogin }}
        </button>
        <Button
            type="submit"
            size="pill"
            class="ml-auto"
            :disabled="processing"
        >
            {{ copy.save }}
        </Button>
    </div>
</template>
