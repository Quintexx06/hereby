<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { adminCopy as copy } from '@/content/admin';
import { plans } from '@/content/publish';
import { unlock } from '@/routes/admin/weddings';
import type { AdminWedding, PlanKey } from '@/types';

const props = defineProps<{ wedding: AdminWedding }>();

const order: PlanKey[] = ['signature', 'atelier', 'couture'];

/* For plans paid by invoice: the team opens the links by hand. */
function choose(plan: PlanKey): void {
    router.patch(
        unlock.url(props.wedding.id),
        { plan },
        { preserveScroll: true },
    );
}
</script>

<!-- The wedding's plan, and the team's way to unlock one paid outside Stripe. -->
<template>
    <DropdownMenu>
        <DropdownMenuTrigger
            class="admin-plan"
            :data-published="wedding.published"
        >
            {{ wedding.plan ? plans[wedding.plan].name : copy.linksClosed }}
            <ChevronDown class="size-3.5" />
        </DropdownMenuTrigger>
        <DropdownMenuContent align="start">
            <DropdownMenuLabel>{{ copy.unlock }}</DropdownMenuLabel>
            <DropdownMenuItem
                v-for="plan in order"
                :key="plan"
                :disabled="wedding.plan === plan"
                @select="choose(plan)"
            >
                {{ plans[plan].name }}
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
