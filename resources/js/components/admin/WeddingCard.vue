<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminUnlock from '@/components/admin/AdminUnlock.vue';
import { adminCopy as copy } from '@/content/admin';
import { lookStyles, steps } from '@/content/setup';
import { formatDate } from '@/lib/format';
import { preview } from '@/routes/weddings';
import { index as content } from '@/routes/weddings/content';
import { index as guests } from '@/routes/weddings/guests';
import { show as setup } from '@/routes/weddings/setup';
import type { AdminWedding } from '@/types';

const props = defineProps<{ wedding: AdminWedding }>();

const isDraft = computed(() => props.wedding.status === 'draft');
const answeredShare = computed(() =>
    props.wedding.households === 0
        ? 0
        : Math.round((props.wedding.answered / props.wedding.households) * 100),
);
</script>

<!-- One wedding as a table row: who, where they stand, when, replies, the way in. -->
<template>
    <li class="admin-row admin-wedding">
        <div class="flex min-w-0 flex-col gap-0.5">
            <p class="truncate text-lg font-semibold">
                {{ wedding.couple_names }}
            </p>
            <p class="truncate text-sm text-muted-foreground">
                {{ wedding.email }}
            </p>
            <p v-if="wedding.look_styles.length" class="admin-row-styles">
                {{
                    wedding.look_styles
                        .map((style) => lookStyles[style].name)
                        .join(' · ')
                }}
            </p>
        </div>

        <div class="flex flex-col items-start gap-1.5">
            <p class="admin-status" :data-status="wedding.status">
                {{
                    isDraft
                        ? copy.draft(steps[wedding.setup_step ?? 'paar'].label)
                        : copy.active
                }}
            </p>
            <AdminUnlock v-if="!isDraft" :wedding="wedding" />
        </div>

        <p class="admin-cell">
            {{ wedding.date ? formatDate(wedding.date, 'de-CH') : copy.noDate }}
        </p>

        <div class="admin-cell">
            <template v-if="!isDraft">
                <p class="tabular-nums">
                    {{ wedding.answered }} /
                    {{ copy.households(wedding.households) }}
                </p>
                <div v-if="wedding.households > 0" class="admin-meter">
                    <span :style="{ width: `${answeredShare}%` }" />
                </div>
            </template>
            <p v-else>–</p>
        </div>

        <p class="admin-row-actions">
            <Link
                v-if="isDraft"
                :href="setup([wedding.id, wedding.setup_step ?? 'paar'])"
                class="text-action hit-area"
                >{{ copy.open }}</Link
            >
            <template v-else>
                <Link :href="guests(wedding.id)" class="text-action hit-area">{{
                    copy.guests
                }}</Link>
                <Link
                    :href="content(wedding.id)"
                    class="text-action hit-area"
                    >{{ copy.content }}</Link
                >
                <a
                    :href="preview.url(wedding.id)"
                    target="_blank"
                    rel="noopener"
                    class="text-action hit-area"
                    >{{ copy.preview }}</a
                >
            </template>
        </p>
    </li>
</template>
