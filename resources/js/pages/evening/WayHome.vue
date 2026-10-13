<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import { Plus, X } from '@lucide/vue';
import EveningLocked from '@/components/evening/EveningLocked.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import SaveBar from '@/components/SaveBar.vue';
import { dashboardPage } from '@/content/dashboard';
import { eveningPage, wayHomePage as copy } from '@/content/evening';
import { firstError } from '@/lib/forms';
import { dashboard } from '@/routes';
import { show as evening } from '@/routes/weddings/evening';
import { update } from '@/routes/weddings/way-home';

type Connection = { label: string; place: string | null; time: string };

const props = defineProps<{
    wedding: { id: number; couple_names: string };
    unlocked: boolean;
    connections: Connection[];
}>();

setLayoutProps({
    breadcrumbs: [
        { title: dashboardPage.title, href: dashboard() },
        { title: eveningPage.title, href: evening(props.wedding.id) },
        { title: copy.title, href: '' },
    ],
});

const form = useForm({
    connections: props.connections.map((c) => ({ ...c, place: c.place ?? '' })),
});

const MAX = 6;
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <EveningLocked
            v-if="!unlocked"
            :wedding-id="wedding.id"
            preview="wayHome"
        />

        <form
            v-else
            class="flex max-w-4xl flex-col gap-6"
            @submit.prevent="
                form.put(update.url(wedding.id), { preserveScroll: true })
            "
        >
            <div
                v-for="(connection, index) in form.connections"
                :key="index"
                class="way-home-row"
            >
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-medium">{{ copy.label }}</span>
                    <input
                        v-model="connection.label"
                        :placeholder="copy.labelPlaceholder"
                        maxlength="60"
                        class="seating-input"
                    />
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-medium">{{ copy.place }}</span>
                    <input
                        v-model="connection.place"
                        :placeholder="copy.placePlaceholder"
                        maxlength="60"
                        class="seating-input"
                    />
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-medium">{{ copy.time }}</span>
                    <input
                        v-model="connection.time"
                        type="time"
                        class="seating-input tabular-nums"
                    />
                </label>
                <button
                    type="button"
                    class="way-home-remove"
                    :aria-label="copy.remove"
                    @click="form.connections.splice(index, 1)"
                >
                    <X class="size-4" />
                </button>
                <p
                    v-if="firstError(form.errors, `connections.${index}`)"
                    class="text-sm text-destructive md:col-span-4"
                >
                    {{ firstError(form.errors, `connections.${index}`) }}
                </p>
            </div>

            <button
                v-if="form.connections.length < MAX"
                type="button"
                class="text-action hit-area self-start"
                @click="
                    form.connections.push({ label: '', place: '', time: '' })
                "
            >
                <Plus class="size-4" /> {{ copy.add }}
            </button>
            <p class="caption">{{ copy.timeHint }}</p>

            <SaveBar
                :processing="form.processing"
                :dirty="form.isDirty"
                :saved="form.recentlySuccessful"
                :label="copy.save"
                :saved-label="copy.saved"
            />
        </form>
    </div>
</template>
