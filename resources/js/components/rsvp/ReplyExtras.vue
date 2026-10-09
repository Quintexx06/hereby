<script setup lang="ts">
import { Minus, Plus } from '@lucide/vue';
import { computed } from 'vue';
import ChoiceChips from '@/components/rsvp/ChoiceChips.vue';
import { useReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';

const { t } = useTrans();
const { form, reply, attendingGuests } = useReplyForm();
const questions = reply.questions;

/* Only people who come can ride the shuttle. */
const seatLimit = computed(
    () => attendingGuests.value.length + (form.plusOne.enabled ? 1 : 0),
);
const yesNo = computed(() => [
    { value: true, label: t('rsvp.yes') },
    { value: false, label: t('rsvp.no') },
]);
const anyQuestion = questions.shuttle || questions.stay || questions.song;
</script>

<!-- The household questions the couple turned on, once someone is coming. -->
<template>
    <section
        v-if="anyQuestion && attendingGuests.length"
        class="reply-section gap-6"
    >
        <h2 class="title">{{ t('rsvp.extras') }}</h2>

        <div v-if="questions.shuttle" class="field">
            <p id="shuttle-label" class="field-label">
                {{ t('rsvp.shuttle') }}
            </p>
            <p class="field-hint">{{ t('rsvp.shuttle_hint') }}</p>
            <div class="stepper self-start" aria-labelledby="shuttle-label">
                <button
                    type="button"
                    class="stepper-button"
                    :aria-label="t('rsvp.fewer')"
                    :disabled="!reply.open || form.shuttleSeats <= 0"
                    @click="form.shuttleSeats--"
                >
                    <Minus class="size-4" />
                </button>
                <output
                    class="w-8 text-center font-semibold tabular-nums"
                    aria-live="polite"
                    >{{ Math.min(form.shuttleSeats, seatLimit) }}</output
                >
                <button
                    type="button"
                    class="stepper-button"
                    :aria-label="t('rsvp.more')"
                    :disabled="!reply.open || form.shuttleSeats >= seatLimit"
                    @click="form.shuttleSeats++"
                >
                    <Plus class="size-4" />
                </button>
            </div>
        </div>

        <div v-if="questions.stay" class="field">
            <p class="field-label">{{ t('rsvp.stay') }}</p>
            <ChoiceChips
                v-model="form.needsStay"
                name="stay"
                :label="t('rsvp.stay')"
                :options="yesNo"
                :disabled="!reply.open"
            />
        </div>

        <div v-if="questions.song" class="field">
            <label for="song" class="field-label">{{ t('rsvp.song') }}</label>
            <input
                id="song"
                v-model="form.songWish"
                type="text"
                class="reply-input"
                maxlength="160"
                autocomplete="off"
                :placeholder="t('rsvp.song_placeholder')"
                :disabled="!reply.open"
            />
        </div>
    </section>
</template>
