<script setup lang="ts">
import CopyLinkButton from '@/components/guests/CopyLinkButton.vue';
import { replyStatus } from '@/content/dashboard';
import { guestsPage } from '@/content/guests';
import { languages } from '@/content/setup';
import type { HouseholdRow } from '@/types';

defineProps<{ household: HouseholdRow }>();
</script>

<template>
    <li class="household-row">
        <div class="min-w-0">
            <p class="truncate font-semibold">{{ household.name }}</p>
            <p class="truncate text-sm text-muted-foreground sm:hidden">
                {{ household.guests.map((guest) => guest.name).join(', ') }}
            </p>
        </div>
        <p
            class="hidden min-w-0 flex-wrap items-center gap-x-2 gap-y-1 text-sm sm:flex"
        >
            <span
                v-for="guest in household.guests"
                :key="guest.id"
                class="inline-flex items-center gap-1.5"
            >
                {{ guest.name }}
                <span v-if="guest.is_child" class="tag">{{
                    guestsPage.child
                }}</span>
            </span>
            <span v-if="household.plus_one_allowed" class="tag">{{
                guestsPage.plusOne
            }}</span>
        </p>
        <p
            class="status-label order-last col-span-2 sm:order-none sm:col-span-1"
        >
            <span
                class="reply-dot"
                :data-status="household.reply_status"
                aria-hidden="true"
            />
            {{ replyStatus[household.reply_status].label }}
            <span class="text-xs">{{ languages[household.locale] }}</span>
        </p>
        <CopyLinkButton :link="household.link" :household="household.name" />
    </li>
</template>
