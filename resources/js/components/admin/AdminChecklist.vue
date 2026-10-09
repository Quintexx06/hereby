<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { computed } from 'vue';
import { adminChecklist as copy } from '@/content/admin';
import { index as inquiries } from '@/routes/admin/inquiries';
import type { AdminWedding } from '@/types';

const props = defineProps<{ weddings: AdminWedding[] }>();
defineEmits<{ hide: [] }>();

const page = usePage();

const steps = computed(
    () =>
        [
            { key: 'couple', done: props.weddings.length > 0 },
            {
                key: 'setup',
                done: props.weddings.some((w) => w.status === 'active'),
            },
            {
                key: 'guests',
                done: props.weddings.some((w) => w.households > 0),
            },
            { key: 'reply', done: props.weddings.some((w) => w.answered > 0) },
            {
                key: 'inbox',
                done: page.props.adminInbox === 0,
                href: inquiries(),
            },
        ] as const,
);

const doneCount = computed(() => steps.value.filter((s) => s.done).length);
</script>

<!-- Starter checklist: every tick comes from real data, never from a click. -->
<template>
    <section class="admin-checklist">
        <div class="flex items-baseline justify-between gap-4">
            <h2 class="ledger-title">{{ copy.title }}</h2>
            <button
                type="button"
                class="link-underline hit-area text-sm text-muted-foreground"
                @click="$emit('hide')"
            >
                {{ copy.hide }}
            </button>
        </div>
        <div class="flex items-center gap-3">
            <div class="admin-meter max-w-none flex-1">
                <span
                    :style="{ width: `${(doneCount / steps.length) * 100}%` }"
                />
            </div>
            <p class="text-sm text-muted-foreground tabular-nums">
                {{ copy.progress(doneCount, steps.length) }}
            </p>
        </div>
        <ol class="flex flex-col">
            <li
                v-for="step in steps"
                :key="step.key"
                class="admin-check"
                :data-done="step.done"
            >
                <span class="admin-check-mark" aria-hidden="true">
                    <Check v-if="step.done" class="size-3.5" />
                </span>
                <Link
                    v-if="'href' in step && !step.done"
                    :href="step.href"
                    class="link-underline"
                    >{{ copy.steps[step.key] }}</Link
                >
                <span v-else>{{ copy.steps[step.key] }}</span>
            </li>
        </ol>
    </section>
</template>
