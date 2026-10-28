<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ExternalLink, Mail } from '@lucide/vue';
import PageMasthead from '@/components/PageMasthead.vue';
import CalendarWeddingRow from '@/components/partners/CalendarWeddingRow.vue';
import PartnerCode from '@/components/partners/PartnerCode.vue';
import { partnerCalendar as copy } from '@/content/partners';
import { formatDate } from '@/lib/format';
import type { CalendarWedding, PartnerType, VenueEnquiry } from '@/types';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Hochzeiten', href: '' }] },
});

defineProps<{
    partner: {
        name: string;
        type: PartnerType;
        type_label: string;
        code: string;
        is_showcase: boolean;
        sees_numbers: boolean;
        page: string | null;
    };
    weddings: CalendarWedding[];
    enquiries: VenueEnquiry[];
}>();
</script>

<!--
    A partner's start page: the code for couples, the connected weddings by
    date (counts and menus, never names), and tour requests for Showcase venues.
-->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead
            :title="copy.title"
            :lede="copy.lede(partner.sees_numbers)"
        >
            <template v-if="partner.page" #actions>
                <a
                    :href="partner.page"
                    target="_blank"
                    rel="noopener"
                    class="text-action hit-area"
                >
                    <ExternalLink class="size-4" /> {{ copy.publicPage }}
                </a>
            </template>
        </PageMasthead>

        <PartnerCode :code="partner.code" />

        <section>
            <p v-if="!weddings.length" class="text-muted-foreground">
                {{ copy.empty }}
            </p>
            <ul v-else class="admin-ledger">
                <CalendarWeddingRow
                    v-for="wedding in weddings"
                    :key="wedding.id"
                    :wedding="wedding"
                    :type="partner.type"
                />
            </ul>
        </section>

        <section v-if="partner.is_showcase" class="ledger-section">
            <h2 class="ledger-title">{{ copy.enquiries }}</h2>
            <p v-if="!enquiries.length" class="text-muted-foreground">
                {{ copy.enquiriesEmpty }}
            </p>
            <ul v-else class="admin-ledger">
                <li
                    v-for="enquiry in enquiries"
                    :key="enquiry.id"
                    class="partner-row"
                >
                    <div class="flex min-w-0 flex-col gap-1">
                        <p class="font-semibold">
                            {{ enquiry.name }}
                            <span
                                v-if="enquiry.preferred_date"
                                class="font-normal text-muted-foreground"
                            >
                                ·
                                {{
                                    copy.wishDate(
                                        formatDate(
                                            enquiry.preferred_date,
                                            'de-CH',
                                        ),
                                    )
                                }}
                            </span>
                        </p>
                        <p v-if="enquiry.message" class="text-sm">
                            {{ enquiry.message }}
                        </p>
                    </div>
                    <a
                        :href="`mailto:${enquiry.email}`"
                        class="witness-action gap-2"
                    >
                        <Mail class="size-4" /> {{ enquiry.email }}
                    </a>
                </li>
            </ul>
        </section>
    </div>
</template>
