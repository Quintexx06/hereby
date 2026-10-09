<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ArrowUpRight,
    ListChecks,
    NotebookPen,
    Users,
    UtensilsCrossed,
} from '@lucide/vue';
import { shortcuts } from '@/content/dashboard';
import { index as content } from '@/routes/weddings/content';
import { index as guests } from '@/routes/weddings/guests';
import { index as kitchen } from '@/routes/weddings/kitchen';
import { edit as rsvpSettings } from '@/routes/weddings/rsvp-settings';

const props = defineProps<{ weddingId: number }>();

const cards = [
    { key: 'guests', icon: Users, href: guests(props.weddingId) },
    { key: 'content', icon: NotebookPen, href: content(props.weddingId) },
    { key: 'rsvp', icon: ListChecks, href: rsvpSettings(props.weddingId) },
    { key: 'kitchen', icon: UtensilsCrossed, href: kitchen(props.weddingId) },
] as const;
</script>

<template>
    <section aria-labelledby="shortcuts" class="flex flex-col gap-4">
        <h2 id="shortcuts" class="app-section-title">{{ shortcuts.title }}</h2>
        <div class="shortcut-grid">
            <Link
                v-for="card in cards"
                :key="card.key"
                :href="card.href"
                class="shortcut-card group"
            >
                <span class="shortcut-icon" aria-hidden="true">
                    <component :is="card.icon" class="size-5" />
                </span>
                <span class="flex flex-col gap-1">
                    <span class="font-semibold">{{
                        shortcuts.items[card.key].title
                    }}</span>
                    <span class="text-sm text-muted-foreground">{{
                        shortcuts.items[card.key].body
                    }}</span>
                </span>
                <ArrowUpRight class="shortcut-arrow" aria-hidden="true" />
            </Link>
        </div>
    </section>
</template>
