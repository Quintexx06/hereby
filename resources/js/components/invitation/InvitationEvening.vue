<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useTrans } from '@/composables/useTrans';
import EveningTables from '@/components/invitation/EveningTables.vue';
import EveningWayHome from '@/components/invitation/EveningWayHome.vue';
import type { Evening } from '@/types';

defineProps<{ evening: Evening | null }>();

const { t } = useTrans();
</script>

<!-- «Der Abend» on the personal link (Atelier and up); nothing otherwise. -->
<template>
    <template v-if="evening">
        <section v-if="evening.call" class="evening-call" role="status">
            <p class="evening-card-title">
                {{ t('invitation.evening.call_heading') }}
            </p>
            <p class="text-lg font-semibold">{{ evening.call.title }}</p>
            <p v-if="evening.call.place">
                {{
                    t('invitation.evening.call_place', {
                        place: evening.call.place,
                    })
                }}
            </p>
        </section>
        <EveningTables v-if="evening.tables.length" :tables="evening.tables" />
        <EveningWayHome
            v-if="evening.wayHome.length"
            :connections="evening.wayHome"
        />
        <section v-if="evening.offerUrl" class="inv-section">
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
