<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { ExternalLink } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { adminBriefs as copy } from '@/content/bespoke';
import { formatDate } from '@/lib/format';
import { update } from '@/routes/admin/briefs';
import { edit as details } from '@/routes/weddings/details';
import type { AdminBrief } from '@/types';

const props = defineProps<{
    brief: AdminBrief;
    statuses: { value: string; label: string }[];
}>();

const form = useForm({
    status: props.brief.status as string,
    team_note: props.brief.team_note ?? '',
});
</script>

<!-- One couple's brief for the team: what they want, and where it stands. -->
<template>
    <article class="brief-card">
        <header class="flex flex-wrap items-baseline justify-between gap-3">
            <h2 class="title">
                {{ brief.couple_names }}
                <span class="text-base font-normal text-muted-foreground">
                    {{ brief.date ? formatDate(brief.date, 'de-CH') : '' }}
                    {{
                        brief.ready_by
                            ? `· ${copy.readyBy(formatDate(brief.ready_by, 'de-CH'))}`
                            : ''
                    }}
                </span>
            </h2>
            <Link
                :href="details([brief.wedding_id, 'look'])"
                class="text-action"
            >
                <ExternalLink class="size-4" /> {{ copy.open }}
            </Link>
        </header>

        <div v-if="brief.colors.length" class="flex gap-2">
            <span
                v-for="color in brief.colors"
                :key="color"
                class="brief-swatch"
                :style="{ background: color }"
                :title="color"
            />
        </div>
        <dl class="brief-facts">
            <dt>{{ copy.wishes }}</dt>
            <dd class="whitespace-pre-line">{{ brief.wishes }}</dd>
            <template v-if="brief.mood">
                <dt>{{ copy.mood }}</dt>
                <dd>{{ brief.mood }}</dd>
            </template>
            <template v-if="brief.links">
                <dt>{{ copy.links }}</dt>
                <dd class="break-all whitespace-pre-line">{{ brief.links }}</dd>
            </template>
        </dl>
        <ul v-if="brief.photos.length" class="brief-thumbs">
            <li v-for="url in brief.photos" :key="url">
                <a :href="url" target="_blank" rel="noopener">
                    <img :src="url" alt="" class="size-full object-cover" />
                </a>
            </li>
        </ul>

        <form
            class="flex flex-col gap-3 border-t border-rule pt-4"
            @submit.prevent="
                form.patch(update.url(brief.id), { preserveScroll: true })
            "
        >
            <div class="chip-row">
                <label
                    v-for="status in statuses"
                    :key="status.value"
                    class="chip"
                >
                    <input
                        v-model="form.status"
                        type="radio"
                        :value="status.value"
                        class="sr-only"
                    />
                    {{ status.label }}
                </label>
            </div>
            <label :for="`note-${brief.id}`" class="field-label">{{
                copy.note
            }}</label>
            <textarea
                :id="`note-${brief.id}`"
                v-model="form.team_note"
                rows="2"
                class="textarea"
            />
            <Button
                type="submit"
                size="pill"
                class="self-start"
                :disabled="form.processing"
            >
                {{ copy.save }}
            </Button>
        </form>
    </article>
</template>
