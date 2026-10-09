<script setup lang="ts">
import { computed } from 'vue';
import ChoiceChips from '@/components/rsvp/ChoiceChips.vue';
import { useReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';
import type { ReplyGuest, ReplyStatus, WeddingEvent } from '@/types';

const props = defineProps<{ guest: ReplyGuest; event: WeddingEvent }>();

const { t } = useTrans();
const { answer, asksMenu, menusFor, reply, form } = useReplyForm();
const current = computed(() => answer(props.guest.id, props.event.id));
const name = computed(() => `${props.guest.id}-${props.event.id}`);

const statuses = computed<{ value: ReplyStatus; label: string }[]>(() => [
    { value: 'attending', label: t('rsvp.attending') },
    { value: 'declined', label: t('rsvp.declined') },
]);
const menus = computed(() =>
    menusFor(props.guest).map((menu) => ({
        value: menu.key,
        label: menu.label,
    })),
);
</script>

<!-- One person at one part of the day: coming or not, and the menu if it applies. -->
<template>
    <div class="reply-person">
        <p class="flex items-center gap-2 font-medium">
            {{ guest.firstName }}
            <span v-if="guest.isChild" class="caption">{{
                t('rsvp.child')
            }}</span>
        </p>
        <ChoiceChips
            v-model="current.status"
            :name="`status-${name}`"
            :label="`${guest.firstName}: ${event.name ?? t(`invitation.event_types.${event.type}`)}`"
            :options="statuses"
            :disabled="!reply.open || form.processing"
        />
        <ChoiceChips
            v-if="current.status === 'attending' && asksMenu(guest, event)"
            v-model="current.menu"
            :name="`menu-${name}`"
            :label="`${t('rsvp.menu')}: ${guest.firstName}`"
            :options="menus"
            :disabled="!reply.open || form.processing"
        />
    </div>
</template>
