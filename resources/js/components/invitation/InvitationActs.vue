<script setup lang="ts">
import { computed } from 'vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import { formatDay, formatTime } from '@/lib/format';
import type { Wedding, WeddingEvent } from '@/types';

/*
 * Atelier's programme, as the landing tells «Ein Tag in fünf Akten»: each
 * part of the day an act on a night band, a photo with its roman numeral,
 * the time large, the place in the accent's italic. On wide screens the
 * row travels sideways as the page scrolls (atelierMotion); on phones the
 * acts stack.
 */
const props = defineProps<{ events: WeddingEvent[]; wedding: Wedding }>();
const { t, localeTag } = useTrans();

const NUMERALS = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];

/* The couple's photos after the cover, taking turns; none, and the act is type alone. */
const photos = computed(() => {
    const rest = props.wedding.photos.slice(1);

    return rest.length ? rest : props.wedding.photos;
});

const route = (event: WeddingEvent): string | null => {
    const place = [event.locationName, event.address]
        .filter(Boolean)
        .join(', ');

    return place
        ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place)}`
        : null;
};
</script>

<template>
    <section class="inv-acts" data-block="programme">
        <h2 class="inv-heading">{{ t('invitation.programme_heading') }}</h2>
        <ol
            class="inv-acts-track"
            tabindex="0"
            :aria-label="t('invitation.programme_heading')"
        >
            <li
                v-for="(event, index) in events"
                :key="event.id"
                class="inv-act"
            >
                <figure class="inv-act-photo" :data-photo="photos.length > 0">
                    <HeroPhoto
                        v-if="photos.length"
                        :photo="photos[index % photos.length]"
                        :alt="
                            t('invitation.photo_alt', {
                                couple: wedding.coupleNames,
                            })
                        "
                        class="size-full"
                    />
                    <span class="inv-act-numeral" aria-hidden="true">{{
                        NUMERALS[index] ?? index + 1
                    }}</span>
                </figure>
                <div class="inv-act-text">
                    <time :datetime="event.startsAt" class="inv-act-time">
                        <span class="inv-act-hour">{{
                            formatTime(event.startsAt, localeTag())
                        }}</span>
                        <span class="caption">{{
                            formatDay(event.startsAt, localeTag())
                        }}</span>
                    </time>
                    <h3 class="inv-act-title">
                        {{
                            event.name ??
                            t(`invitation.event_types.${event.type}`)
                        }}
                    </h3>
                    <p v-if="event.locationName" class="inv-act-place">
                        {{ event.locationName }}
                    </p>
                    <p
                        v-if="event.address"
                        class="text-sm text-muted-foreground"
                    >
                        {{ event.address }}
                    </p>
                    <a
                        v-if="route(event)"
                        :href="route(event) ?? undefined"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="block-route link-underline hit-area mt-2 text-sm font-medium"
                        >{{ t('invitation.route') }}</a
                    >
                </div>
            </li>
        </ol>
    </section>
</template>
