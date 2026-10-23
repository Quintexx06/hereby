<script setup lang="ts">
import type { ProgrammeItem } from '@/composables/useInvitationContent';
import { useTrans } from '@/composables/useTrans';
import { eventIcons } from '@/lib/invitation-icons';

defineProps<{ items: ProgrammeItem[] }>();
const { t } = useTrans();
</script>

<!--
    The day as the lanes of the pool: one lane for each part, numbered at
    the start block, the time big in Shrikhand, the place at the far end;
    lane ropes of beads between them.
-->
<template>
    <ol class="lido-lanes">
        <li
            v-for="(item, index) in items"
            :key="item.event.id"
            class="lido-lane"
        >
            <span class="lido-lane-number" aria-hidden="true">{{
                index + 1
            }}</span>
            <time :datetime="item.event.startsAt" class="lido-lane-time">{{
                item.time
            }}</time>
            <div class="lido-lane-what">
                <component
                    :is="eventIcons[item.event.type]"
                    class="lido-lane-icon"
                    aria-hidden="true"
                />
                <h3 class="lido-lane-title">{{ item.title }}</h3>
            </div>
            <div class="lido-lane-where">
                <p v-if="item.event.locationName" class="font-semibold">
                    {{ item.event.locationName }}
                </p>
                <p v-if="item.event.address" class="text-muted-foreground">
                    {{ item.event.address }}
                </p>
                <a
                    v-if="item.route"
                    :href="item.route"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inv-day-route"
                    >{{ t('invitation.route') }}</a
                >
            </div>
        </li>
    </ol>
</template>
