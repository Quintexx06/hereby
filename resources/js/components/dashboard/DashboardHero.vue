<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Eye, UserPlus } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { dashboardHero as copy } from '@/content/dashboard';
import { landingPhotos } from '@/content/landing-photos';
import { formatDate } from '@/lib/format';
import { preview } from '@/routes/weddings';
import { index as guests } from '@/routes/weddings/guests';
import type { DashboardWedding } from '@/types';

const props = defineProps<{
    wedding: DashboardWedding;
    daysLeft: number | null;
}>();

const photo = landingPhotos.lakeJetty;
const subtitle = computed(() =>
    [
        props.wedding.date ? formatDate(props.wedding.date, 'de-CH') : null,
        props.wedding.venue,
    ]
        .filter(Boolean)
        .join(' · '),
);
</script>

<!-- The couple's opening band: their names over the lake, the days to go. -->
<template>
    <section class="dashboard-hero stage">
        <img
            :src="photo.src"
            :srcset="photo.srcset"
            sizes="(min-width: 1024px) 60rem, 100vw"
            alt=""
            class="dashboard-hero-photo"
            fetchpriority="high"
        />
        <div class="dashboard-hero-veil" aria-hidden="true" />

        <div class="relative flex flex-col gap-4">
            <h1 class="dashboard-hero-names">{{ wedding.couple_names }}</h1>
            <p class="text-lg text-muted-foreground">{{ subtitle }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
                <Button as-child size="pill">
                    <a
                        :href="preview.url(wedding.id)"
                        target="_blank"
                        rel="noopener"
                    >
                        <Eye /> {{ copy.preview }}
                    </a>
                </Button>
                <Button as-child size="pill" variant="outline">
                    <Link :href="guests(wedding.id)">
                        <UserPlus /> {{ copy.addGuests }}
                    </Link>
                </Button>
            </div>
        </div>

        <p v-if="daysLeft !== null" class="dashboard-countdown">
            <span class="dashboard-countdown-number">{{
                daysLeft >= 0 ? daysLeft : '♥'
            }}</span>
            <span class="text-sm text-muted-foreground">{{
                daysLeft >= 0 ? copy.countdownLabel(daysLeft) : copy.celebrated
            }}</span>
        </p>
    </section>
</template>
