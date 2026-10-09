<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { adminCopy as copy } from '@/content/admin';
import { lookStyles, steps } from '@/content/setup';
import { formatDate } from '@/lib/format';
import { preview } from '@/routes/weddings';
import { index as content } from '@/routes/weddings/content';
import { index as guests } from '@/routes/weddings/guests';
import { show as setup } from '@/routes/weddings/setup';
import type { AdminWedding } from '@/types';

const props = defineProps<{ wedding: AdminWedding }>();

/** "Anna & Luca" → "A&L", the couple's monogram. */
const monogram = computed(() =>
    props.wedding.couple_names
        .split(/\s*(?:&|und)\s*/)
        .map((name) => name.trim().charAt(0))
        .filter(Boolean)
        .join('&'),
);

const isDraft = computed(() => props.wedding.status === 'draft');
const answeredShare = computed(() =>
    props.wedding.households === 0
        ? 0
        : Math.round((props.wedding.answered / props.wedding.households) * 100),
);
</script>

<template>
    <li class="admin-card">
        <span class="admin-monogram" aria-hidden="true">{{ monogram }}</span>

        <div class="flex min-w-0 flex-col gap-1">
            <div class="flex flex-wrap items-center gap-2">
                <p class="truncate font-semibold">{{ wedding.couple_names }}</p>
                <span class="admin-status" :data-status="wedding.status">
                    {{ isDraft ? copy.draftBadge : copy.active }}
                </span>
            </div>
            <p class="truncate text-sm text-muted-foreground">
                {{ wedding.email }}
            </p>
            <p class="text-sm text-muted-foreground tabular-nums">
                <template v-if="isDraft">
                    {{ copy.draft(steps[wedding.setup_step ?? 'paar'].label) }}
                </template>
                <template v-else>
                    {{ copy.households(wedding.households) }},
                    {{ copy.answered(wedding.answered) }}
                </template>
                <template v-if="wedding.date">
                    · {{ formatDate(wedding.date, 'de-CH') }}
                </template>
            </p>
            <p v-if="wedding.look_styles.length" class="text-sm">
                {{
                    wedding.look_styles
                        .map((style) => lookStyles[style].name)
                        .join(' · ')
                }}
            </p>
            <p
                v-if="wedding.look_wishes"
                class="admin-wish"
                :title="wedding.look_wishes"
            >
                «{{ wedding.look_wishes }}»
            </p>
            <div v-if="!isDraft && wedding.households > 0" class="admin-meter">
                <span :style="{ width: `${answeredShare}%` }" />
            </div>
        </div>

        <p class="admin-card-actions">
            <Link
                v-if="isDraft"
                :href="setup([wedding.id, wedding.setup_step ?? 'paar'])"
                class="link-underline hit-area"
                >{{ copy.open }}</Link
            >
            <template v-else>
                <Link
                    :href="guests(wedding.id)"
                    class="link-underline hit-area"
                    >{{ copy.guests }}</Link
                >
                <Link
                    :href="content(wedding.id)"
                    class="link-underline hit-area"
                    >{{ copy.content }}</Link
                >
                <a
                    :href="preview.url(wedding.id)"
                    target="_blank"
                    rel="noopener"
                    class="link-underline hit-area"
                    >{{ copy.preview }}</a
                >
            </template>
        </p>
    </li>
</template>
