<script setup lang="ts">
import { computed } from 'vue';
import ChoiceChips from '@/components/rsvp/ChoiceChips.vue';
import { useReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';

const { t } = useTrans();
const { form, reply, menusFor, plusOneAtDinner, attendingGuests } =
    useReplyForm();

const menus = computed(() =>
    menusFor(null).map((menu) => ({ value: menu.key, label: menu.label })),
);
const choices = computed(() => [
    { value: true, label: t('rsvp.plus_one_add') },
    { value: false, label: t('rsvp.plus_one_remove') },
]);
</script>

<!-- A plus-one is a person with a name, not a "+1" counter. -->
<template>
    <section
        v-if="reply.household.plusOneAllowed && attendingGuests.length"
        class="reply-section"
    >
        <div class="flex flex-col gap-1">
            <h2 class="title">{{ t('rsvp.plus_one') }}</h2>
            <p class="field-hint">{{ t('rsvp.plus_one_hint') }}</p>
        </div>
        <ChoiceChips
            v-model="form.plusOne.enabled"
            name="plus-one"
            :label="t('rsvp.plus_one')"
            :options="choices"
            :disabled="!reply.open"
        />
        <template v-if="form.plusOne.enabled">
            <div class="field">
                <label for="plus-one-name" class="field-label">{{
                    t('rsvp.plus_one_name')
                }}</label>
                <input
                    id="plus-one-name"
                    v-model="form.plusOne.firstName"
                    type="text"
                    class="reply-input"
                    maxlength="80"
                    autocomplete="off"
                    :disabled="!reply.open"
                />
            </div>
            <ChoiceChips
                v-if="plusOneAtDinner"
                v-model="form.plusOne.menu"
                name="plus-one-menu"
                :label="t('rsvp.menu')"
                :options="menus"
                :disabled="!reply.open"
            />
        </template>
    </section>
</template>
