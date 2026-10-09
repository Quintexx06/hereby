<script setup lang="ts">
import { computed } from 'vue';
import { Spinner } from '@/components/ui/spinner';
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
                <Spinner v-if="form.processing" />
                {{ reply.answered ? t('rsvp.update') : t('rsvp.submit') }}
            </button>
        </div>
    </div>
</template>
