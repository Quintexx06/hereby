<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import PageMasthead from '@/components/PageMasthead.vue';
import ConfirmButton from '@/components/guests/editor/ConfirmButton.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { dashboardPage } from '@/content/dashboard';
import { weddingPartners as copy } from '@/content/partners';
import { dashboard } from '@/routes';
import { destroy, store } from '@/routes/weddings/partners';
import type { ConnectedPartner } from '@/types';

const props = defineProps<{
    wedding: { id: number };
    partners: ConnectedPartner[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});

const form = useForm({ code: '' });

function connect(): void {
    form.post(store.url(props.wedding.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function remove(partner: ConnectedPartner): void {
    router.delete(destroy.url([props.wedding.id, partner.id]), {
        preserveScroll: true,
    });
}
</script>

<!-- The couple's partners: connect one by code, see what it sees, let it go. -->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <form class="partner-connect" @submit.prevent="connect">
            <div class="grid flex-1 gap-2">
                <label for="partner-code" class="field-label">{{
                    copy.code
                }}</label>
                <Input
                    id="partner-code"
                    v-model="form.code"
                    :placeholder="copy.codePlaceholder"
                    autocomplete="off"
                    autocapitalize="characters"
                    class="uppercase"
                />
                <InputError :message="form.errors.code" />
            </div>
            <Button type="submit" size="pill" :disabled="form.processing">
                {{ copy.connect }}
            </Button>
        </form>

        <p v-if="!partners.length" class="text-muted-foreground">
            {{ copy.empty }}
        </p>
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
                            · {{ partner.type_label
                            }}{{ partner.town ? `, ${partner.town}` : '' }}
                        </span>
                    </p>
                    <p class="field-hint">
                        {{
                            partner.sees_numbers
                                ? copy.seesNumbers
                                : copy.seesPhotos
                        }}
                    </p>
                </div>
                <ConfirmButton
                    :label="copy.remove"
                    :question="copy.removeQuestion"
                    @confirm="remove(partner)"
                />
            </li>
        </ul>
    </div>
</template>
