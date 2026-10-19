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

<!--
    Starter checklist as one strip across the page: five steps side by side,
    each tick derived from real data, never from a click.
-->
<template>
    <section
        v-if="doneCount < steps.length"
        class="admin-checklist"
        data-tour="checklist"
    >
        <div class="admin-checklist-head">
            <h2 class="ledger-title">{{ copy.title }}</h2>
            <p class="text-sm text-muted-foreground tabular-nums">
                {{ copy.progress(doneCount, steps.length) }}
            </p>
            <button
                type="button"
                class="link-underline hit-area ml-auto text-sm text-muted-foreground"
                @click="$emit('hide')"
            >
                {{ copy.hide }}
            </button>
        </div>
        <ol class="admin-steps">
            <li
                v-for="(step, stepIndex) in steps"
                :key="step.key"
                class="admin-check"
                :data-done="step.done"
            >
                <span class="admin-check-mark" aria-hidden="true">
                    <Check v-if="step.done" class="size-3.5" />
                    <template v-else>{{ stepIndex + 1 }}</template>
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
