<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import MenuOptionsField from '@/components/rsvp/MenuOptionsField.vue';
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
    };
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
});

const toggles = [
    { field: 'offers_shuttle', label: copy.shuttle },
    { field: 'offers_stay', label: copy.stay },
    { field: 'asks_song', label: copy.song },
] as const;
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <header class="flex max-w-2xl flex-col gap-2">
            <h1 class="app-title">{{ copy.title }}</h1>
            <p class="text-muted-foreground">{{ copy.lede }}</p>
        </header>

        <div class="rsvp-settings-grid">
            <form
                class="flex flex-col gap-10"
                @submit.prevent="
                    form.put(update.url(wedding.id), { preserveScroll: true })
                "
            >
                <section class="settings-section">
                    <h2 class="app-section-title">{{ copy.always }}</h2>
                    <ul class="flex flex-col gap-2 text-muted-foreground">
                        <li v-for="item in copy.alwaysItems" :key="item">
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

                <section class="settings-section">
                    <h2 class="app-section-title">{{ copy.household }}</h2>
                    <div class="flex flex-col">
                        <label
                            v-for="toggle in toggles"
                            :key="toggle.field"
                            class="check-label"
                        >
                            <input
                                v-model="form[toggle.field]"
                                type="checkbox"
                                class="checkbox"
                            />
                            {{ toggle.label }}
                        </label>
                    </div>
                </section>

                <Button
                    type="submit"
                    size="pill"
                    class="self-start"
                    :disabled="form.processing"
                >
                    <Spinner v-if="form.processing" />
                    {{ copy.save }}
                </Button>
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
