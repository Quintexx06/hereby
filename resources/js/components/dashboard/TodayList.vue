<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
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
    <section aria-labelledby="today" class="ledger-section">
        <h2 id="today" class="ledger-title">{{ active.today }}</h2>
        <p v-if="actions.length === 0" class="text-muted-foreground">
            {{ active.nothingToday }}
        </p>
        <div v-else>
            <Link
                v-for="item in actions"
                :key="item.key"
                :href="href(item.key)"
                class="ledger-link group"
            >
                <span class="flex flex-col gap-1">
                    <span class="text-lg font-semibold">{{
                        todayActions[item.key].title
                    }}</span>
                    <span class="text-muted-foreground">{{
                        todayActions[item.key].body(item.count)
                    }}</span>
                </span>
                <ArrowRight class="ledger-arrow" aria-hidden="true" />
            </Link>
        </div>
    </section>
</template>
