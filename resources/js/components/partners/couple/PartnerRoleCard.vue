<script setup lang="ts">
import { Building2, Camera, ClipboardList, Mail, Plus } from '@lucide/vue';
import ConfirmButton from '@/components/guests/editor/ConfirmButton.vue';
import { weddingPartners as copy } from '@/content/partners';
import { formatDate } from '@/lib/format';
import type { ConnectedPartner, PartnerInviteRow, PartnerType } from '@/types';

const props = defineProps<{
    type: PartnerType;
    partners: ConnectedPartner[];
    invites: PartnerInviteRow[];
}>();

const emit = defineEmits<{
    invite: [type: PartnerType];
    remove: [partner: ConnectedPartner];
    withdraw: [invite: PartnerInviteRow];
}>();

const icons = {
    venue: Building2,
    photographer: Camera,
    planner: ClipboardList,
};
const role = copy.roles[props.type];
</script>

<!-- One role (venue, photographer, planner): who is connected, who is invited. -->
<template>
    <article
        class="partner-role"
        :data-connected="partners.length > 0 || undefined"
    >
        <header class="flex items-center gap-3">
            <span class="partner-role-icon" aria-hidden="true">
                <component :is="icons[type]" class="size-5" />
            </span>
            <h2 class="text-lg font-semibold">{{ role.label }}</h2>
        </header>
        <p class="text-sm text-muted-foreground">{{ role.sees }}</p>

        <ul v-if="partners.length || invites.length" class="partner-role-list">
            <li
                v-for="partner in partners"
                :key="partner.id"
                class="partner-entry"
            >
                <div class="min-w-0">
                    <p class="truncate font-semibold">{{ partner.name }}</p>
                    <p class="caption">
                        {{ partner.town ? `${partner.town} · ` : ''
                        }}{{
                            partner.since
                                ? copy.since(formatDate(partner.since, 'de-CH'))
                                : ''
                        }}
                    </p>
                </div>
                <ConfirmButton
                    :label="copy.remove"
                    :question="copy.removeQuestion"
                    @confirm="emit('remove', partner)"
                />
            </li>
            <li
                v-for="invite in invites"
                :key="`i${invite.id}`"
                class="partner-entry"
            >
                <div class="min-w-0">
                    <p class="truncate font-semibold">{{ invite.name }}</p>
                    <p class="caption inline-flex items-center gap-1.5">
                        <Mail class="size-3.5" aria-hidden="true" />
                        {{ copy.invited }}
                    </p>
                </div>
                <button
                    type="button"
                    class="text-action text-sm"
                    @click="emit('withdraw', invite)"
                >
                    {{ copy.withdraw }}
                </button>
            </li>
        </ul>

        <button
            type="button"
            class="partner-role-add"
            @click="emit('invite', type)"
        >
            <Plus class="size-4" aria-hidden="true" />
            {{ role.label }} {{ copy.invite.toLowerCase() }}
        </button>
    </article>
</template>
