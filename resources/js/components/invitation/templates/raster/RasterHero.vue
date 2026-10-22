<script setup lang="ts">
import { computed } from 'vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingPhoto } from '@/types';

const props = defineProps<{
    names: { first: string; second: string };
    date: { numeric: string; weekday: string };
    place: string | null;
    cover: WeddingPhoto | null;
    alt: string;
}>();
const { t } = useTrans();

/* «19.06.2027» breaks after the month on phones: «19.06.» / «2027». */
const dateParts = computed(() => {
    const match = /^(.*\D)(\d{4})$/.exec(props.date.numeric);

    return match ? [match[1], match[2]] : [props.date.numeric, ''];
});
</script>

<!--
    Raster opens with the date as architecture: the Swiss numerals across
    the whole grid, then the couple's names on the left and their photo on
    the right, sharing a baseline. Flush left, square, nothing moves.
-->
<template>
    <header class="raster-hero">
        <p class="raster-date" aria-hidden="true">
            <span>{{ dateParts[0] }}</span
            ><span>{{ dateParts[1] }}</span>
        </p>
        <div class="raster-hero-names">
            <h1 class="raster-names">
                {{ names.first }}
                <template v-if="names.second">
                    &amp;<br />{{ names.second }}
                </template>
                <span class="sr-only">{{ t('invitation.marry') }}</span>
            </h1>
            <p class="raster-meta">
                {{ date.weekday }}, {{ date.numeric
                }}<template v-if="place"><br />{{ place }}</template>
            </p>
        </div>
        <figure v-if="cover" class="raster-cover">
            <HeroPhoto :photo="cover" :alt="alt" eager class="size-full" />
        </figure>
    </header>
</template>
