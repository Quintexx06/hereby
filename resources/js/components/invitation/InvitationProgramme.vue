<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';
import type { ProgrammeItem } from '@/composables/useInvitationContent';
import { formatDay } from '@/lib/format';

/* Days shows the date under each time, for weddings over more than one day. */
defineProps<{ items: ProgrammeItem[]; days?: boolean }>();
const { t, localeTag } = useTrans();
</script>

<!--
    The day's parts in one list. Each design sets it its own way (a
    timetable, an engraved order of the day, a newspaper table), so the
    markup stays plain: time, what, where, the way there.
-->
<template>
    <ol class="inv-day">
        <li
            v-for="item in items"
            :key="item.event.id"
            class="inv-day-item"
            :data-type="item.event.type"
        >
            <time :datetime="item.event.startsAt" class="inv-day-time">
                {{ item.time }}
                <span v-if="days" class="inv-day-date">{{
                    formatDay(item.event.startsAt, localeTag())
                }}</span>
            </time>
            <div class="inv-day-body">
                <h3 class="inv-day-title">{{ item.title }}</h3>
                <p v-if="item.event.locationName" class="inv-day-place">
                    {{ item.event.locationName }}
                </p>
                <p v-if="item.event.address" class="inv-day-address">
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
