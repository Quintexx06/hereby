<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { X } from '@lucide/vue';
import { ref } from 'vue';
import { useTrans } from '@/composables/useTrans';
import EveningTables from '@/components/invitation/EveningTables.vue';
import EveningWayHome from '@/components/invitation/EveningWayHome.vue';
import type { Evening, EveningLook } from '@/types';

const { look = { section: 'inv-section', heading: 'inv-heading' } } =
    defineProps<{ evening: Evening | null; look?: EveningLook }>();

const { t } = useTrans();
const dismissed = ref(false);
</script>

<!--
    «Der Abend» on the personal link (Atelier and up); nothing otherwise.
    Each design hands in its own section and heading classes (`look`).
-->
<template>
    <template v-if="evening">
        <!-- Called now: fixed at the foot of the screen, wherever the guest is. -->
        <div
            v-if="evening.call && !dismissed"
            class="evening-call"
            role="status"
        >
            <div class="flex items-start justify-between gap-4">
                <p class="evening-card-title">
                    {{ t('invitation.evening.call_heading') }}
                </p>
                <button
                    type="button"
                    class="evening-call-close"
                    :aria-label="t('invitation.evening.call_close')"
                    @click="dismissed = true"
                >
                    <X class="size-5" />
                </button>
            </div>
            <p class="text-lg font-semibold">{{ evening.call.title }}</p>
            <p v-if="evening.call.place">
                {{
                    t('invitation.evening.call_place', {
                        place: evening.call.place,
                    })
                }}
            </p>
        </div>
        <EveningTables
            v-if="evening.tables.length"
            :tables="evening.tables"
            :look="look"
        />
        <EveningWayHome
            v-if="evening.wayHome.length"
            :connections="evening.wayHome"
            :look="look"
        />
        <section v-if="evening.offerUrl" :class="look.section">
            <div class="evening-card items-start">
                <p class="evening-card-title">
                    {{ t('invitation.programme.title') }}
                </p>
                <p class="text-muted-foreground">
                    {{ t('invitation.programme.offer_body') }}
                </p>
                <Link :href="evening.offerUrl" class="reply-send mt-2">
                    {{ t('invitation.programme.offer_cta') }}
                </Link>
            </div>
        </section>
    </template>
</template>
