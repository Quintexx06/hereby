<script setup lang="ts">
import type { ProgrammeItem } from '@/composables/useInvitationContent';
import { useTrans } from '@/composables/useTrans';

defineProps<{ items: ProgrammeItem[] }>();
const { t } = useTrans();
</script>

<!--
    The day as a Swiss trail signpost: one yellow sign for each part on a
    post, pointing out in turn, with the time where the walking time would
    stand. A sign with a place leads to the route.
-->
<template>
    <ol class="arve-post">
        <li
            v-for="item in items"
            :key="item.event.id"
            class="arve-post-item"
            :data-type="item.event.type"
        >
            <component
                :is="item.route ? 'a' : 'div'"
                :href="item.route ?? undefined"
                :target="item.route ? '_blank' : undefined"
                :rel="item.route ? 'noopener noreferrer' : undefined"
                class="arve-sign"
            >
                <span class="arve-sign-to">{{ item.title }}</span>
                <time :datetime="item.event.startsAt" class="arve-sign-time">{{
                    item.time
                }}</time>
                <span
                    v-if="item.day || item.event.locationName"
                    class="arve-sign-place"
                >
                    {{
                        [item.day, item.event.locationName]
                            .filter(Boolean)
                            .join(' · ')
                    }}
                </span>
                <span v-if="item.route" class="sr-only">{{
                    t('invitation.route')
                }}</span>
            </component>
            <p v-if="item.event.address" class="arve-post-address">
                {{ item.event.address }}
            </p>
        </li>
    </ol>
</template>
