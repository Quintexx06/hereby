<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { active, replyStatus } from '@/content/dashboard';
import { index as guests } from '@/routes/weddings/guests';
import type { ReplyStatusKey, WeddingOverview } from '@/types';

const props = defineProps<{
    weddingId: number;
    overview: WeddingOverview;
}>();

const order: ReplyStatusKey[] = ['answered', 'opened', 'never_opened'];
const total = computed(() => Math.max(props.overview.households, 1));

/* Before anyone has opened a link, a full grey bar would read as "done". */
const quiet = computed(
    () => props.overview.replies.answered + props.overview.replies.opened === 0,
);
</script>

<!-- Where every household stands (roadmap 1.11), as one bar and three rows. -->
<template>
    <section aria-labelledby="replies" class="ledger-section">
        <div>
            <h2 id="replies" class="ledger-title">{{ active.replies }}</h2>
            <p class="ledger-hint">
                {{ active.counts(overview.households, overview.guests) }}
            </p>
        </div>
        <div class="flex flex-col gap-4">
            <p v-if="quiet" class="text-muted-foreground">
                {{ active.noRepliesYet }}
            </p>
            <div v-else class="reply-bar" aria-hidden="true">
                <span
                    v-for="status in order"
                    :key="status"
                    class="reply-part"
                    :data-status="status"
                    :style="{
                        flexGrow: overview.replies[status],
                        flexBasis: 0,
                    }"
                />
            </div>
            <ul v-if="!quiet">
                <li
                    v-for="status in order"
                    :key="status"
                    class="app-row grid-cols-[auto_minmax(0,1fr)_auto] py-3.5"
                >
                    <span
                        class="reply-dot"
                        :data-status="status"
                        aria-hidden="true"
                    />
                    <span>
                        <span class="font-medium">{{
                            replyStatus[status].label
                        }}</span>
                        <span class="block text-sm text-muted-foreground">{{
                            replyStatus[status].hint
                        }}</span>
                    </span>
                    <span class="text-right font-semibold tabular-nums">
                        {{ overview.replies[status] }}
                        <span
                            class="block text-xs font-normal text-muted-foreground"
                            >{{
                                Math.round(
                                    (overview.replies[status] / total) * 100,
                                )
                            }}%</span
                        >
                    </span>
                </li>
            </ul>
            <Link
                :href="guests(weddingId)"
                class="text-action hit-area self-start"
                >{{ active.toGuests }}</Link
            >
        </div>
    </section>
</template>
