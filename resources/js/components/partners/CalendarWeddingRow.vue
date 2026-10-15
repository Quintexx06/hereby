<script setup lang="ts">
import { Camera, Printer } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';
import { partnerCalendar as copy } from '@/content/partners';
import { formatDate } from '@/lib/format';
import { show as photos } from '@/routes/partner/photos';
import { sheet } from '@/routes/partner';
import type { CalendarWedding, PartnerType } from '@/types';

defineProps<{ wedding: CalendarWedding; type: PartnerType }>();
</script>

<!-- One connected wedding: date, couple, counts, menus, and the action. -->
<template>
    <li class="partner-wedding">
        <p class="partner-wedding-date">
            {{ wedding.date ? formatDate(wedding.date, 'de-CH') : '–' }}
        </p>
        <div class="flex min-w-0 flex-col gap-2">
            <p class="text-lg font-semibold">{{ wedding.couple_names }}</p>
            <p class="text-sm text-muted-foreground">
                {{ copy.households(wedding.answered, wedding.households) }} ·
                {{ copy.attending(wedding.attending) }} ·
                {{
                    wedding.open && wedding.deadline
                        ? copy.open(formatDate(wedding.deadline, 'de-CH'))
                        : wedding.open
                          ? ''
                          : copy.closed
                }}
            </p>
            <ul v-if="wedding.events.length" class="partner-events">
                <li v-for="event in wedding.events" :key="event.name">
                    <span class="font-semibold tabular-nums">{{
                        event.attending
                    }}</span>
                    {{ event.name }}
                    <span
                        v-if="event.menus.length"
                        class="text-muted-foreground"
                    >
                        ({{
                            event.menus
                                .map((menu) => `${menu.count} ${menu.label}`)
                                .join(', ')
                        }})
                    </span>
                </li>
            </ul>
        </div>
        <a
            v-if="type !== 'photographer'"
            :href="sheet.url(wedding.id)"
            target="_blank"
            class="witness-action gap-2"
        >
            <Printer class="size-4" /> {{ copy.sheet }}
        </a>
        <Link
            v-else-if="wedding.uploads"
            :href="photos(wedding.id)"
            class="witness-action gap-2"
        >
            <Camera class="size-4" /> {{ copy.upload }}
        </Link>
        <span v-else class="caption">{{ copy.noUpload }}</span>
    </li>
</template>
