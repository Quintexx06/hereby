<script setup lang="ts">
import { ArrowDown } from '@lucide/vue';
import { computed } from 'vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import { daysUntil } from '@/lib/calendar';
import { formatDate } from '@/lib/format';
import type { Wedding } from '@/types';

const props = defineProps<{
    wedding: Wedding;
    place: string | null;
}>();

const { t, tc, localeTag } = useTrans();

/* "Anna & Luca": the ampersand in a light tint of the accent. */
const names = computed(() => {
    const [first, ...rest] = props.wedding.coupleNames.split(/\s+&\s+/);

    return { first, second: rest.join(' & ') };
});
const cover = computed(() => props.wedding.photos[0] ?? null);
</script>

<!--
    Atelier, as on the landing: the names set large over the couple's photo,
    the date and place beneath them. Without a photo, the same frame at night.
-->
<template>
    <header class="inv-hero">
        <div class="inv-stage" :data-photo="cover !== null">
            <HeroPhoto
                v-if="cover"
                :photo="cover"
                :alt="
                    t('invitation.photo_alt', { couple: wedding.coupleNames })
                "
                eager
                class="inv-stage-photo"
            />
            <div class="inv-stage-text">
                <h1 class="inv-names">
                    <span class="inv-name">{{ names.first }}</span>
                    <template v-if="names.second">
                        <span class="inv-amp">&amp;</span>
                        <span class="inv-name">{{ names.second }}</span>
                    </template>
                    <span class="sr-only">{{ t('invitation.marry') }}</span>
                </h1>
                <p class="inv-stage-meta">
                    {{ formatDate(wedding.date, localeTag())
                    }}<template v-if="place"> · {{ place }}</template>
                </p>
            </div>
        </div>

        <p class="invitation-countdown">
            {{ tc('invitation.countdown', daysUntil(wedding.date)) }}
        </p>

        <a href="#inv-letter" class="inv-scroll">
            <ArrowDown class="size-4" aria-hidden="true" />
            {{ t('invitation.scroll') }}
        </a>
    </header>
</template>
