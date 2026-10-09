<script setup lang="ts">
import { computed } from 'vue';
import { LoaderCircle } from '@lucide/vue';
import { useReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';

const { t, tc } = useTrans();
const { form, reply, missing, submit } = useReplyForm();

/* Server errors arrive keyed by field; the guest needs the first one, in words. */
const error = computed(() => Object.values(form.errors)[0]);
</script>

<!-- Always in reach of a thumb: what is still open, and send. -->
<template>
    <div v-if="reply.open" class="reply-footer">
        <div class="reply-footer-inner">
            <p
                class="text-sm"
                :class="error ? 'text-destructive' : 'text-muted-foreground'"
                aria-live="polite"
            >
                {{
                    error ??
                    (missing > 0
                        ? tc('rsvp.missing', missing)
                        : t('rsvp.ready'))
                }}
            </p>
            <button
                type="button"
                class="reply-send"
                :disabled="missing > 0 || form.processing"
                @click="submit"
            >
                <!-- No ui/spinner here: it pulls in cn() and tailwind-merge (rule 2). -->
                <LoaderCircle
                    v-if="form.processing"
                    class="size-4 animate-spin"
                    role="status"
                    :aria-label="t('rsvp.sending')"
                />
                {{ reply.answered ? t('rsvp.update') : t('rsvp.submit') }}
            </button>
        </div>
    </div>
</template>
