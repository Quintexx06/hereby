<script setup lang="ts">
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingPhoto } from '@/types';

defineProps<{
    names: { first: string; second: string };
    date: { weekday: string; long: string };
    place: string | null;
    cover: WeddingPhoto | null;
    alt: string;
}>();
const { t } = useTrans();
</script>

<!--
    Lido opens like a summer day at the lake bath: the striped awning, a
    butter-yellow field with the names set big in Gloock, and the couple's
    photo beside it as a square postcard in a paper border.
-->
<template>
    <header class="lido-hero">
        <div class="lido-stripe" aria-hidden="true" />
        <div class="lido-hero-grid">
            <div class="lido-field">
                <p class="lido-kicker">{{ t('invitation.save_the_date') }}</p>
                <h1 class="lido-names">
                    {{ names.first }}
                    <template v-if="names.second">
                        <span class="lido-amp">&amp;</span>
                        {{ names.second }}
                    </template>
                    <span class="sr-only">{{ t('invitation.marry') }}</span>
                </h1>
                <p class="lido-when">
                    {{ date.weekday }}, {{ date.long
                    }}<template v-if="place"> · {{ place }}</template>
                </p>
            </div>
            <figure v-if="cover" class="lido-postcard">
                <HeroPhoto :photo="cover" :alt="alt" eager class="size-full" />
            </figure>
        </div>
    </header>
</template>
