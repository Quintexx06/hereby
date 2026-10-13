<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useTrans } from '@/composables/useTrans';

export type ProgrammeOffer = {
    id: number;
    kind: string;
    title: string;
    minutes: number;
    status: 'pending' | 'accepted' | 'declined';
    withdrawUrl: string | null;
};

defineProps<{ items: ProgrammeOffer[] }>();

const { t } = useTrans();
</script>

<!-- What the household already signed up, and where each one stands. -->
<template>
    <section v-if="items.length" class="flex flex-col gap-3">
        <h2 class="text-sm font-semibold text-muted-foreground">
            {{ t('invitation.programme.yours') }}
        </h2>
        <ul class="evening-card evening-mates">
            <li v-for="item in items" :key="item.id">
                <span class="flex flex-col">
                    <span class="font-semibold">{{ item.title }}</span>
                    <span class="text-sm text-muted-foreground">
                        {{ t(`invitation.programme.kinds.${item.kind}`) }} ·
                        {{
                            t('invitation.programme.minutes_short', {
                                count: item.minutes,
                            })
                        }}
                    </span>
                </span>
                <span class="evening-countdown">{{
                    t(`invitation.programme.status.${item.status}`)
                }}</span>
                <button
                    v-if="item.withdrawUrl"
                    type="button"
                    class="text-sm underline underline-offset-4"
                    @click="
                        router.delete(item.withdrawUrl, {
                            preserveScroll: true,
                        })
                    "
                >
                    {{ t('invitation.programme.withdraw') }}
                </button>
            </li>
        </ul>
    </section>
</template>
