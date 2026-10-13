<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Check, Mail, Phone } from '@lucide/vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { chaseCopy as copy } from '@/content/admin';
import { index, update } from '@/routes/admin/chase';

type ChaseHousehold = {
    id: number;
    name: string;
    email: string | null;
    phone: string | null;
    opened: boolean;
    chased_at: string | null;
};

defineOptions({
    layout: { breadcrumbs: [{ title: 'Nachfassen', href: index() }] },
});

defineProps<{
    weddings: {
        id: number;
        couple_names: string;
        email: string;
        deadline: string | null;
        households: ChaseHousehold[];
    }[];
}>();

function mark(household: ChaseHousehold): void {
    router.patch(
        update.url(household.id),
        { chased: household.chased_at === null },
        { preserveScroll: true },
    );
}
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <PageMasthead :title="copy.title" :lede="copy.lede" scene="ribbon" />

        <p v-if="!weddings.length" class="admin-empty">{{ copy.empty }}</p>

        <section
            v-for="wedding in weddings"
            :key="wedding.id"
            class="ledger-section"
        >
            <h2 class="ledger-title">
                {{ wedding.couple_names }}
                <span class="text-muted-foreground">
                    {{
                        wedding.deadline ? copy.deadline(wedding.deadline) : ''
                    }}
                    ·
                    {{ wedding.email }}
                </span>
            </h2>
            <ul class="admin-ledger">
                <li
                    v-for="household in wedding.households"
                    :key="household.id"
                    class="witness-item"
                    :data-status="household.chased_at ? 'declined' : 'pending'"
                >
                    <div class="flex min-w-0 flex-col gap-1">
                        <p class="font-semibold">{{ household.name }}</p>
                        <p class="caption">
                            {{ household.opened ? copy.opened : copy.unopened }}
                        </p>
                        <p class="flex flex-wrap gap-x-4 gap-y-1 text-sm">
                            <a
                                v-if="household.phone"
                                :href="`tel:${household.phone}`"
                                class="inline-flex items-center gap-1.5"
                            >
                                <Phone class="size-3.5" /> {{ household.phone }}
                            </a>
                            <a
                                v-if="household.email"
                                :href="`mailto:${household.email}`"
                                class="inline-flex items-center gap-1.5"
                            >
                                <Mail class="size-3.5" /> {{ household.email }}
                            </a>
                            <span
                                v-if="!household.phone && !household.email"
                                class="text-muted-foreground"
                            >
                                {{ copy.noContact }}
                            </span>
                        </p>
                    </div>
                    <button
                        type="button"
                        class="witness-action gap-2"
                        @click="mark(household)"
                    >
                        <Check v-if="household.chased_at" class="size-4" />
                        {{
                            household.chased_at ? copy.chased : copy.markChased
                        }}
                    </button>
                </li>
            </ul>
        </section>
    </div>
</template>
