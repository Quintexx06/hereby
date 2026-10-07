<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';
import { formatDate } from '@/lib/format';

defineProps<{
    rsvpDeadline: string | null;
}>();

const { t, localeTag } = useTrans();
</script>

<template>
    <footer class="mt-auto flex flex-col items-center gap-6 pt-16 text-center">
        <p v-if="rsvpDeadline" class="text-lg font-semibold">
            {{
                t('invitation.reply_by', {
                    date: formatDate(rsvpDeadline, localeTag()),
                })
            }}
        </p>
        <p class="text-sm text-muted-foreground">
            {{ t('invitation.replies_open_soon') }}
        </p>
        <hr class="rule" />
        <p class="caption">{{ t('common.credit') }}</p>
    </footer>
</template>
