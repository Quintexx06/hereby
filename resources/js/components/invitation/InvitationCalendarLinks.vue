<script setup lang="ts">
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { googleCalendarUrl, webcalUrl } from '@/lib/calendar';
import type { Invitation } from '@/types';

const props = defineProps<{ invitation: Invitation }>();
const { t } = useTrans();

const links = computed(() => {
    const own = props.invitation.links;

    if (!own || !props.invitation.events.length) {
        return null;
    }

    const google = googleCalendarUrl(
        t('invitation.title', { couple: props.invitation.wedding.coupleNames }),
        props.invitation.events,
        own.invitation,
    );

    return [
        {
            label: t('invitation.calendar_ics'),
            href: own.calendar,
            download: true,
        },
        ...(google
            ? [
                  {
                      label: t('invitation.calendar_google'),
                      href: google,
                      external: true,
                  },
              ]
            : []),
        {
            label: t('invitation.calendar_subscribe'),
            href: webcalUrl(own.calendar),
        },
    ];
});
</script>

<!-- «Haltet euch den Tag frei»: the date into every calendar, one line. -->
<template>
    <p v-if="links" class="inv-calendar">
        <span class="inv-calendar-label">{{
            t('invitation.calendar_heading')
        }}</span>
        <a
            v-for="link in links"
            :key="link.label"
            :href="link.href"
            :download="'download' in link || undefined"
            :target="'external' in link ? '_blank' : undefined"
            :rel="'external' in link ? 'noopener noreferrer' : undefined"
            class="inv-calendar-link"
            >{{ link.label }}</a
        >
    </p>
</template>
