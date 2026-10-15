<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { Handshake } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { weddingPartners as copy } from '@/content/partners';
import { index, store } from '@/routes/weddings/partners';
import type { SuggestedPartner } from '@/types';

const props = defineProps<{ weddingId: number; partner: SuggestedPartner }>();

const form = useForm({ code: props.partner.code });
</script>

<!-- The couple came through a partner's link: one click to connect. -->
<template>
    <section class="partner-suggestion stage" aria-live="polite">
        <span class="partner-role-icon" aria-hidden="true">
            <Handshake class="size-5" />
        </span>
        <div class="flex min-w-0 flex-1 flex-col gap-1">
            <p class="text-lg font-semibold text-balance">
                {{ copy.suggestion(partner.name) }}
            </p>
            <p class="caption">
                {{ partner.type_label
                }}{{ partner.town ? `, ${partner.town}` : '' }} ·
                {{ copy.suggestionLede }}
            </p>
        </div>
        <div class="flex items-center gap-4">
            <Button
                size="pill"
                :disabled="form.processing"
                @click="
                    form.post(store.url(weddingId), { preserveScroll: true })
                "
            >
                {{ copy.connect }}
            </Button>
            <Link :href="index(weddingId)" class="text-action" preserve-scroll>
                {{ copy.notNow }}
            </Link>
        </div>
    </section>
</template>
