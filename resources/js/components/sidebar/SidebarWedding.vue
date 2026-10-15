<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { active, sidebarWedding } from '@/content/dashboard';
import { plans } from '@/content/publish';
import { steps } from '@/content/setup';
import { formatDate } from '@/lib/format';
import { daysUntil } from '@/lib/setup';
import { show as publish } from '@/routes/weddings/publish';
import type { SetupStepKey } from '@/types';

const page = usePage();
const wedding = computed(() => page.props.currentWedding);
const partner = computed(() => page.props.partner);
const partnerTypes = {
    venue: 'Location',
    photographer: 'Fotograf:in',
    planner: 'Planer:in',
};

const details = computed(() => {
    const current = wedding.value;

    if (!current) {
        return null;
    }

    if (current.status === 'draft') {
        return sidebarWedding.inSetup(
            steps[(current.setup_step ?? 'paar') as SetupStepKey].label,
        );
    }

    return current.date ? formatDate(current.date, 'de-CH') : null;
});

const countdown = computed(() =>
    wedding.value?.status === 'active' && wedding.value.date
        ? active.daysLeft(daysUntil(wedding.value.date))
        : null,
);
</script>

<!--
    Whose wedding this is: the couple's names, the date and the countdown.
    For a partner account, the partner's name and what it is.
-->
<template>
    <div v-if="wedding" class="app-nav-wide flex-col gap-1 px-3 pt-2 pb-6">
        <p
            class="font-display text-2xl leading-tight font-semibold tracking-[-0.03em] text-balance"
        >
            {{ wedding.couple_names || sidebarWedding.fallbackName }}
        </p>
        <p v-if="details" class="text-sm text-muted-foreground">
            {{ details }}
        </p>
        <p v-if="countdown" class="text-sm font-medium text-brand">
            {{ countdown }}
        </p>
        <Link
            v-if="wedding.status === 'active'"
            :href="publish(wedding.id)"
            class="sidebar-plan"
            :data-published="wedding.published"
        >
            {{
                wedding.plan
                    ? sidebarWedding.plan(plans[wedding.plan].name)
                    : sidebarWedding.linksClosed
            }}
        </Link>
    </div>
    <div v-else-if="partner" class="app-nav-wide flex-col gap-1 px-3 pt-2 pb-6">
        <p
            class="font-display text-2xl leading-tight font-semibold tracking-[-0.03em] text-balance"
        >
            {{ partner.name }}
        </p>
        <p class="text-sm text-muted-foreground">
            {{ partnerTypes[partner.type]
            }}{{ partner.is_showcase ? ' · Showcase' : '' }}
        </p>
    </div>
</template>
