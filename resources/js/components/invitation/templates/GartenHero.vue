<script setup lang="ts">
import { computed, ref } from 'vue';
import GuestScene from '@/components/invitation/GuestScene.vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import GartenBranch from '@/components/invitation/templates/GartenBranch.vue';
import { useTrans } from '@/composables/useTrans';
import { daysUntil } from '@/lib/calendar';
import { formatDate } from '@/lib/format';
import type { Wedding } from '@/types';

const props = defineProps<{ wedding: Wedding; place: string | null }>();
const { t, tc, localeTag } = useTrans();

const names = computed(() => {
    const [first, ...rest] = props.wedding.coupleNames.split(/\s+&\s+/);

    return { first, second: rest.join(' & ') };
});
const cover = computed(() => props.wedding.photos[0] ?? null);
/* Without a photo the arch holds their initials. */
const initials = computed(() =>
    [names.value.first, names.value.second]
        .filter(Boolean)
        .map((name) => name.trim().charAt(0))
        .join(' & '),
);

/* Real petals in 3D once three.js is in; the CSS ones fall until then. */
const petals3d = ref(false);
const petals = () =>
    import('@/lib/three/petals').then(
        (scene) => (canvas: HTMLCanvasElement, animate: boolean) =>
            scene.createPetals(canvas, animate, {
                count: 28,
                size: 0.42,
                spread: 1.8,
            }),
    );
</script>

<!--
    Garten, as on the landing: the couple's photo under an arch, a branch
    growing on either side, the names centred below; petals fall.
-->
<template>
    <header class="garten-hero">
        <GuestScene
            :load="petals"
            class="garten-petals-3d"
            @ready="petals3d = true"
        />
        <div v-if="!petals3d" class="garten-petals" aria-hidden="true">
            <span v-for="petal in 9" :key="petal" :style="{ '--p': petal }" />
        </div>
        <GartenBranch side="left" />
        <GartenBranch side="right" />

        <p class="text-sm font-medium text-muted-foreground">
            {{ t('invitation.save_the_date') }}
        </p>
        <div class="garten-arch" :data-photo="cover !== null">
            <HeroPhoto
                v-if="cover"
                :photo="cover"
                :alt="
                    t('invitation.photo_alt', { couple: wedding.coupleNames })
                "
                eager
                class="size-full"
            />
            <span v-else class="garten-initials" aria-hidden="true">{{
                initials
            }}</span>
        </div>
        <h1 class="garten-names">
            {{ names.first }}
            <template v-if="names.second">
                <span class="garten-amp">&amp;</span> {{ names.second }}
            </template>
            <span class="sr-only">{{ t('invitation.marry') }}</span>
        </h1>
        <p class="font-medium text-muted-foreground">
            {{ formatDate(wedding.date, localeTag())
            }}<template v-if="place"> · {{ place }}</template>
        </p>
        <p class="invitation-countdown">
            {{ tc('invitation.countdown', daysUntil(wedding.date)) }}
        </p>
    </header>
</template>
