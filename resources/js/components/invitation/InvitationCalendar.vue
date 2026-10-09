<script setup lang="ts">
import { CalendarPlus, RefreshCw } from '@lucide/vue';
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { googleCalendarUrl, webcalUrl } from '@/lib/calendar';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation }>();

const { t } = useTrans();

const links = computed(() => {
    const own = props.invitation.links;

    if (!own) {
        return null;
    }

    return {
        ics: own.calendar,
        google: googleCalendarUrl(
            t('invitation.title', {
                couple: props.invitation.wedding.coupleNames,
            }),
            props.invitation.events,
            own.invitation,
        ),
        subscribe: webcalUrl(own.calendar),
    };
});
</script>

<!-- Every guest can keep the date, before and after they reply. -->
<template>
    <section v-if="invitation.events.length" class="invitation-section">
        <h2 class="invitation-heading">
            {{ t('invitation.calendar_heading') }}
        </h2>
        <div class="calendar-options">
            <!-- The couple's preview has no personal links: show, don't link. -->
            <component
                :is="links ? 'a' : 'span'"
                :href="links?.ics"
                :download="links ? true : undefined"
                class="calendar-option"
            >
                <CalendarPlus class="size-4" aria-hidden="true" />
                {{ t('invitation.calendar_ics') }}
            </component>
            <component
                :is="links?.google ? 'a' : 'span'"
                :href="links?.google ?? undefined"
                target="_blank"
                rel="noopener noreferrer"
                class="calendar-option"
            >
                <CalendarPlus class="size-4" aria-hidden="true" />
                {{ t('invitation.calendar_google') }}
            </component>
            <component
                :is="links ? 'a' : 'span'"
                :href="links?.subscribe"
                class="calendar-option"
            >
                <RefreshCw class="size-4" aria-hidden="true" />
                {{ t('invitation.calendar_subscribe') }}
            </component>
        </div>
        <p class="caption text-center">{{ t('invitation.calendar_hint') }}</p>
    </section>
</template>
