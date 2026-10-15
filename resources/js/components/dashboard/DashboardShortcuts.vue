<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import { shortcuts } from '@/content/dashboard';
import { index as content } from '@/routes/weddings/content';
import { edit as details } from '@/routes/weddings/details';
import { index as guests } from '@/routes/weddings/guests';
import { index as kitchen } from '@/routes/weddings/kitchen';
import { index as memories } from '@/routes/weddings/memories';
import { index as partners } from '@/routes/weddings/partners';
import { edit as rsvpSettings } from '@/routes/weddings/rsvp-settings';

const props = defineProps<{ weddingId: number }>();

const rows = [
    { key: 'guests', href: guests(props.weddingId) },
    { key: 'details', href: details([props.weddingId, 'paar']) },
    { key: 'content', href: content(props.weddingId) },
    { key: 'rsvp', href: rsvpSettings(props.weddingId) },
    { key: 'kitchen', href: kitchen(props.weddingId) },
    { key: 'memories', href: memories(props.weddingId) },
    { key: 'partners', href: partners(props.weddingId) },
] as const;
</script>

<!-- A table of contents for the rest of the app, set like a programme. -->
<template>
    <section aria-labelledby="shortcuts" class="ledger-section">
        <h2 id="shortcuts" class="ledger-title">{{ shortcuts.title }}</h2>
        <div>
            <Link
                v-for="row in rows"
                :key="row.key"
                :href="row.href"
                class="ledger-link group"
            >
                <span class="grid gap-1 sm:grid-cols-[12rem_minmax(0,1fr)]">
                    <span class="text-lg font-semibold">{{
                        shortcuts.items[row.key].title
                    }}</span>
                    <span class="text-muted-foreground">{{
                        shortcuts.items[row.key].body
                    }}</span>
                </span>
                <ArrowRight class="ledger-arrow" aria-hidden="true" />
            </Link>
        </div>
    </section>
</template>
