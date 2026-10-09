<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { inquiriesCopy as copy } from '@/content/admin';
import { formatDate } from '@/lib/format';
import { update } from '@/routes/admin/inquiries';
import type { AdminInquiry } from '@/types';

const props = defineProps<{ inquiry: AdminInquiry }>();

const mailto = computed(
    () =>
        `mailto:${props.inquiry.email}?subject=${encodeURIComponent(copy.replySubject)}`,
);

function toggle(): void {
    router.patch(
        update.url(props.inquiry.id),
        { answered: !props.inquiry.answered },
        { preserveScroll: true },
    );
}
</script>

<template>
    <li class="admin-row admin-inquiry" :data-answered="inquiry.answered">
        <div class="flex min-w-0 flex-col gap-2">
            <p class="flex flex-wrap items-baseline gap-x-3 text-sm">
                <span class="truncate font-semibold">{{ inquiry.email }}</span>
                <span v-if="inquiry.created_at" class="text-muted-foreground">
                    {{ formatDate(inquiry.created_at, 'de-CH') }}
                </span>
            </p>
            <p class="text-pretty whitespace-pre-line">
                {{ inquiry.question }}
            </p>
        </div>

        <div class="admin-row-actions">
            <a
                v-if="!inquiry.answered"
                :href="mailto"
                class="text-action hit-area"
                >{{ copy.reply }}</a
            >
            <Button
                size="sm"
                :variant="inquiry.answered ? 'ghost' : 'outline'"
                class="rounded-full"
                @click="toggle"
            >
                {{ inquiry.answered ? copy.reopen : copy.markAnswered }}
            </Button>
        </div>
    </li>
</template>
