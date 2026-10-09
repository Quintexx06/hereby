<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { active, todayActions } from '@/content/dashboard';
import { index as guests } from '@/routes/weddings/guests';
import { show } from '@/routes/weddings/setup';
import type { WeddingOverview } from '@/types';

const props = defineProps<{
    weddingId: number;
    actions: WeddingOverview['actions'];
}>();

/* Every action leads to the guest list, except the deadline (wedding details). */
const href = (key: string) =>
    key === 'set_deadline'
        ? show([props.weddingId, 'datum'])
        : guests(props.weddingId);
</script>

<template>
    <section aria-labelledby="today">
        <h2 id="today" class="app-section-title mb-2">{{ active.today }}</h2>
        <p v-if="actions.length === 0" class="app-row text-muted-foreground">
            {{ active.nothingToday }}
        </p>
        <div
            v-for="item in actions"
            :key="item.key"
            class="app-row grid-cols-1 sm:grid-cols-[minmax(0,1fr)_auto]"
        >
            <div class="flex flex-col gap-1">
                <p class="font-semibold">{{ todayActions[item.key].title }}</p>
                <p class="text-muted-foreground">
                    {{ todayActions[item.key].body(item.count) }}
                </p>
            </div>
            <Link
                :href="href(item.key)"
                class="link-underline justify-self-start font-medium sm:justify-self-end"
            >
                {{ todayActions[item.key].cta }}
            </Link>
        </div>
    </section>
</template>
