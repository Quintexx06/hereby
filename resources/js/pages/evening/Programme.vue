<script setup lang="ts">
import { Head, router, setLayoutProps, useForm } from '@inertiajs/vue3';
import CopyLink from '@/components/CopyLink.vue';
import EveningLocked from '@/components/evening/EveningLocked.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import SaveBar from '@/components/SaveBar.vue';
import { dashboardPage } from '@/content/dashboard';
import { eveningPage, programmePage as copy } from '@/content/evening';
import { dashboard } from '@/routes';
import { show as evening } from '@/routes/weddings/evening';
import { renew, update } from '@/routes/weddings/programme';

const props = defineProps<{
    wedding: { id: number; couple_names: string };
    unlocked: boolean;
    witnessLink: string | null;
    summary: {
        total: number;
        accepted: number;
        minutes: number;
        budget: number | null;
    };
}>();

setLayoutProps({
    breadcrumbs: [
        { title: dashboardPage.title, href: dashboard() },
        { title: eveningPage.title, href: evening(props.wedding.id) },
        { title: copy.title, href: '' },
    ],
});

const form = useForm({ programme_minutes: props.summary.budget });
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <EveningLocked
            v-if="!unlocked"
            :wedding-id="wedding.id"
            preview="programme"
        />

        <template v-else>
            <p class="caption">{{ copy.summary(summary) }}</p>

            <div v-if="witnessLink" class="flex flex-col items-start gap-3">
                <CopyLink
                    id="witness-link"
                    :link="witnessLink"
                    :label="copy.linkLabel"
                    :hint="copy.linkHint"
                />
                <button
                    type="button"
                    class="text-action hit-area"
                    :title="copy.renewHint"
                    @click="
                        router.post(
                            renew.url(wedding.id),
                            {},
                            { preserveScroll: true },
                        )
                    "
                >
                    {{ copy.renew }}
                </button>
            </div>

            <form
                class="flex max-w-md flex-col gap-3"
                @submit.prevent="
                    form.put(update.url(wedding.id), { preserveScroll: true })
                "
            >
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-medium">{{ copy.budget }}</span>
                    <input
                        v-model.number="form.programme_minutes"
                        type="number"
                        min="5"
                        max="600"
                        class="seating-input w-32 tabular-nums"
                    />
                </label>
                <p class="field-hint">{{ copy.budgetHint }}</p>
                <SaveBar
                    :processing="form.processing"
                    :dirty="form.isDirty"
                    :saved="form.recentlySuccessful"
                    :label="copy.save"
                    :saved-label="copy.saved"
                />
            </form>
        </template>
    </div>
</template>
