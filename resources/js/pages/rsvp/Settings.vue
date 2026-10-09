<script setup lang="ts">
import PageMasthead from '@/components/PageMasthead.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import MenuOptionsField from '@/components/rsvp/MenuOptionsField.vue';
import ReminderSettings from '@/components/rsvp/ReminderSettings.vue';
import RsvpPreview from '@/components/rsvp/RsvpPreview.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { dashboardPage } from '@/content/dashboard';
import { rsvpSettings as copy } from '@/content/rsvp';
import { firstError } from '@/lib/forms';
import { dashboard } from '@/routes';
import { update } from '@/routes/weddings/rsvp-settings';
import type { WeddingTheme } from '@/types';

const props = defineProps<{
    wedding: { id: number; couple_names: string; theme: WeddingTheme };
    settings: {
        menus: { key: string; label: string }[];
        children_menu: boolean;
        offers_shuttle: boolean;
        offers_stay: boolean;
        asks_song: boolean;
        sends_reminders: boolean;
    };
    reminderDates: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});

/* Keys travel with labels, so renaming a menu keeps guests' choices. */
const form = useForm({
    menus: props.settings.menus.map((menu) => menu.label),
    menu_keys: props.settings.menus.map((menu) => menu.key as string | null),
    children_menu: props.settings.children_menu,
    offers_shuttle: props.settings.offers_shuttle,
    offers_stay: props.settings.offers_stay,
    asks_song: props.settings.asks_song,
    sends_reminders: props.settings.sends_reminders,
});

const toggles = [
    { field: 'offers_shuttle', label: copy.shuttle, hint: copy.shuttleHint },
    { field: 'offers_stay', label: copy.stay, hint: copy.stayHint },
    { field: 'asks_song', label: copy.song, hint: copy.songHint },
] as const;
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" plain />

        <div class="rsvp-settings-grid">
            <form
                class="settings-cards"
                @submit.prevent="
                    form.put(update.url(wedding.id), { preserveScroll: true })
                "
            >
                <section class="settings-section">
                    <h2 class="ledger-title">{{ copy.always }}</h2>
                    <ul class="always-list">
                        <li v-for="item in copy.alwaysItems" :key="item">
                            <Check class="size-4 shrink-0 text-brand" />
                            {{ item }}
                        </li>
                    </ul>
                </section>

                <MenuOptionsField
                    v-model:menus="form.menus"
                    v-model:keys="form.menu_keys"
                    v-model:children-menu="form.children_menu"
                    :error="firstError(form.errors, 'menus')"
                />

                <ReminderSettings
                    v-model="form.sends_reminders"
                    :dates="reminderDates"
                />

                <section class="settings-section">
                    <h2 class="ledger-title">{{ copy.household }}</h2>
                    <div class="toggle-tiles">
                        <label
                            v-for="toggle in toggles"
                            :key="toggle.field"
                            class="toggle-tile"
                        >
                            <input
                                v-model="form[toggle.field]"
                                type="checkbox"
                                class="checkbox mt-0.5"
                            />
                            <span class="flex flex-col gap-1">
                                <span class="font-medium">{{
                                    toggle.label
                                }}</span>
                                <span class="text-sm text-muted-foreground">{{
                                    toggle.hint
                                }}</span>
                            </span>
                        </label>
                    </div>
                </section>

                <div class="settings-savebar">
                    <Button
                        type="submit"
                        size="pill"
                        :disabled="form.processing || !form.isDirty"
                    >
                        <Spinner v-if="form.processing" />
                        {{ copy.save }}
                    </Button>
                </div>
            </form>

            <aside class="rsvp-preview-panel stage" :aria-label="copy.preview">
                <RsvpPreview
                    :theme="wedding.theme"
                    :menus="form.menus.filter((menu) => menu.trim())"
                    :shuttle="form.offers_shuttle"
                    :stay="form.offers_stay"
                    :song="form.asks_song"
                />
            </aside>
        </div>
    </div>
</template>
