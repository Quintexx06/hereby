<script setup lang="ts">
import { Minus, Plus } from '@lucide/vue';
import { ref } from 'vue';
import AllergyField from '@/components/rsvp/AllergyField.vue';
import { useReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';

const { t } = useTrans();
const { form, reply, attendingGuests } = useReplyForm();

/* Closed by default: most people have nothing to add. Open if something is on file. */
const open = ref(
    reply.guests.some((guest) => guest.hasDietaryNotes) ||
        Boolean(reply.plusOne?.hasDietaryNotes),
);
</script>

<template>
    <section v-if="attendingGuests.length" class="reply-section">
        <button
            type="button"
            class="check-label self-start font-medium"
            :aria-expanded="open"
            aria-controls="allergies"
            @click="open = !open"
        >
            <Minus v-if="open" class="size-4" aria-hidden="true" />
            <Plus v-else class="size-4" aria-hidden="true" />
            {{ t('rsvp.allergies_toggle') }}
        </button>
        <div v-show="open" id="allergies" class="flex flex-col gap-5">
            <AllergyField
                v-for="guest in attendingGuests"
                :id="`allergies-${guest.id}`"
                :key="guest.id"
                v-model="form.notes[guest.id]"
                :name="guest.firstName"
                :on-file="guest.hasDietaryNotes"
                :disabled="!reply.open"
            />
            <AllergyField
                v-if="form.plusOne.enabled && form.plusOne.firstName.trim()"
                id="allergies-plus-one"
                v-model="form.plusOne"
                :name="form.plusOne.firstName"
                :on-file="Boolean(reply.plusOne?.hasDietaryNotes)"
                :disabled="!reply.open"
            />
            <p class="field-hint">{{ t('rsvp.allergies_private') }}</p>
        </div>
    </section>
</template>
