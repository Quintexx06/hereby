<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { adminPartners as copy } from '@/content/partners';
import { destroy } from '@/routes/admin/partners';

const props = defineProps<{
    partnerId: number;
    hasLogin: boolean;
    canInvite: boolean;
    /** A prospect without sign-in or weddings: it can ask to be forgotten. */
    removable: boolean;
    processing: boolean;
}>();
defineEmits<{ invite: [] }>();
const confirming = ref(false);

/* First tap asks, second tap deletes. */
function remove(): void {
    if (!confirming.value) {
        confirming.value = true;

        return;
    }

    router.delete(destroy.url(props.partnerId), { preserveScroll: true });
}
</script>

<!-- The dialog's foot: sign-in link, deleting a prospect, and save. -->
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
        <button
            v-if="removable"
            type="button"
            class="text-action text-sm text-destructive"
            :disabled="processing"
            @click="remove"
        >
            {{ confirming ? copy.removeQuestion : copy.remove }}
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
