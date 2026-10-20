<script setup lang="ts">
import { ArrowDown } from '@lucide/vue';
import { computed } from 'vue';
import GuestScene from '@/components/invitation/GuestScene.vue';
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

/* "Anna & Luca": the ampersand in the accent's italic. */
const names = computed(() => {
    const [first, ...rest] = props.wedding.coupleNames.split(/\s+&\s+/);

    return { first, second: rest.join(' & ') };
});
const cover = computed(() => props.wedding.photos[0] ?? null);
const days = computed(() => daysUntil(props.wedding.date));

const sparkler = () =>
    import('@/lib/three/sparkler').then((scene) => scene.createSparkler);
</script>

<!--
    Atelier, as on the landing: the couple's photo edge to edge, a sparkler
    drawing a heart of light over it, their names set large, and the days
    to go as a figure. Without a photo, the same stage at night.
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
            <GuestScene :load="sparkler" class="inv-stage-sparkler" />
            <div class="inv-stage-text">
                <h1 class="inv-names">
                    <span class="inv-name">{{ names.first }}</span>
                    <template v-if="names.second">
                        <span class="inv-amp">&amp;</span>
                        <span class="inv-name">{{ names.second }}</span>
                    </template>
                    <span class="sr-only">{{ t('invitation.marry') }}</span>
                </h1>
                <div class="inv-stage-foot">
                    <p class="inv-stage-meta">
                        {{ formatDate(wedding.date, localeTag())
                        }}<template v-if="place"> · {{ place }}</template>
                    </p>
                    <p class="inv-countdown">
                        <span v-if="days > 0" class="inv-countdown-figure">{{
                            days
                        }}</span>
                        <span class="inv-countdown-unit">{{
                            tc('invitation.countdown_unit', days)
                        }}</span>
                    </p>
                </div>
            </div>
            <a href="#inv-letter" class="inv-scroll">
                <ArrowDown class="size-4" aria-hidden="true" />
                {{ t('invitation.scroll') }}
            </a>
        </div>
    </header>
</template>
