<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import NewCoupleForm from '@/components/admin/NewCoupleForm.vue';
import { adminCopy as copy } from '@/content/admin';
import { steps } from '@/content/setup';
import { formatDate } from '@/lib/format';
import { preview } from '@/routes/weddings';
import { index as content } from '@/routes/weddings/content';
import { index as guests } from '@/routes/weddings/guests';
import { show as setup } from '@/routes/weddings/setup';
import type { SetupStepKey } from '@/types';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Admin', href: '' }] },
});

defineProps<{
    weddings: {
        id: number;
        couple_names: string;
        email: string;
        status: 'draft' | 'active';
        setup_step: SetupStepKey | null;
        date: string | null;
        households: number;
        answered: number;
        created_at: string | null;
    }[];
}>();
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <header class="flex max-w-2xl flex-col gap-2">
            <h1 class="app-title">{{ copy.title }}</h1>
            <p class="text-muted-foreground">{{ copy.lede }}</p>
        </header>

        <NewCoupleForm class="max-w-2xl" />

        <p v-if="weddings.length === 0" class="text-muted-foreground">
            {{ copy.empty }}
        </p>
        <ul v-else class="border-b">
            <li v-for="wedding in weddings" :key="wedding.id" class="admin-row">
                <div class="min-w-0">
                    <p class="truncate font-semibold">
                        {{ wedding.couple_names }}
                    </p>
                    <p class="truncate text-sm text-muted-foreground">
                        {{ wedding.email }}
                    </p>
                </div>
                <p class="text-sm text-muted-foreground">
                    {{
                        wedding.status === 'draft'
                            ? copy.draft(
                                  steps[wedding.setup_step ?? 'paar'].label,
                              )
                            : copy.active
                    }}<template v-if="wedding.date"
                        >, {{ formatDate(wedding.date, 'de-CH') }}</template
                    >
                </p>
                <p class="text-sm text-muted-foreground tabular-nums">
                    {{ copy.households(wedding.households) }},
                    {{ copy.answered(wedding.answered) }}
                </p>
                <p
                    class="flex flex-wrap gap-x-4 text-sm font-medium sm:justify-end"
                >
                    <Link
                        v-if="wedding.status === 'draft'"
                        :href="
                            setup([wedding.id, wedding.setup_step ?? 'paar'])
                        "
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
        </ul>
    </div>
</template>
