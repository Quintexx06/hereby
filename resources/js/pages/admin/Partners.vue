<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import PageMasthead from '@/components/PageMasthead.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { adminPartners as copy } from '@/content/partners';
import { index, store, update } from '@/routes/admin/partners';
import type { AdminPartner, PartnerType } from '@/types';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Partner', href: index() }] },
});

defineProps<{ partners: AdminPartner[] }>();

const types: Record<PartnerType, string> = {
    venue: 'Location',
    photographer: 'Fotograf:in',
    planner: 'Planer:in',
};

const form = useForm({ type: 'venue' as PartnerType, name: '', email: '' });

function create(): void {
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset('name', 'email'),
    });
}

function toggleShowcase(partner: AdminPartner): void {
    router.patch(
        update.url(partner.id),
        { is_showcase: !partner.is_showcase },
        { preserveScroll: true },
    );
}
</script>

<!-- The team's partners: open an account, mark a venue as Showcase. -->
<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <form class="partner-create" @submit.prevent="create">
            <fieldset class="chip-row sm:col-span-3">
                <legend class="field-label mb-2">{{ copy.type }}</legend>
                <label
                    v-for="(label, value) in types"
                    :key="value"
                    class="chip"
                >
                    <input
                        v-model="form.type"
                        type="radio"
                        :value="value"
                        class="sr-only"
                    />
                    {{ label }}
                </label>
            </fieldset>
            <div class="grid gap-2">
                <label for="partner-name" class="field-label">{{
                    copy.name
                }}</label>
                <Input id="partner-name" v-model="form.name" required />
                <InputError :message="form.errors.name" />
            </div>
            <div class="grid gap-2">
                <label for="partner-email" class="field-label">{{
                    copy.email
                }}</label>
                <Input
                    id="partner-email"
                    v-model="form.email"
                    type="email"
                    autocomplete="off"
                    required
                />
                <InputError :message="form.errors.email" />
            </div>
            <Button
                type="submit"
                size="pill"
                class="self-end"
                :disabled="form.processing"
            >
                {{ copy.create }}
            </Button>
        </form>

        <p v-if="!partners.length" class="admin-empty">{{ copy.empty }}</p>
        <ul v-else class="admin-ledger">
            <li
                v-for="partner in partners"
                :key="partner.id"
                class="partner-row"
            >
                <div class="flex min-w-0 flex-col gap-1">
                    <p class="font-semibold">
                        {{ partner.name }}
                        <span class="font-normal text-muted-foreground">
                            · {{ partner.type_label }} ·
                            {{ copy.weddings(partner.weddings) }}
                        </span>
                    </p>
                    <p class="caption">
                        <span class="partner-code">{{ partner.code }}</span>
                        · {{ partner.login }}
                    </p>
                </div>
                <button
                    v-if="partner.type === 'venue'"
                    type="button"
                    class="witness-action"
                    :aria-pressed="partner.is_showcase"
                    @click="toggleShowcase(partner)"
                >
                    {{ copy.showcase }}{{ partner.is_showcase ? ' ✓' : '' }}
                </button>
            </li>
        </ul>
    </div>
</template>
