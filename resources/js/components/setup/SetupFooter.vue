<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { actions } from '@/content/setup';
import { show } from '@/routes/weddings/setup';
import type { SetupStepKey } from '@/types/setup';

/* Back, a quiet "Gespeichert" after each save, and the way forward. */
defineProps<{
    weddingId: number;
    previous?: SetupStepKey;
    saved: boolean;
    processing: boolean;
    isReview: boolean;
}>();
</script>

<template>
    <div class="setup-footer">
        <Button v-if="previous" variant="ghost" size="pill" as-child>
            <Link :href="show([weddingId, previous])">{{ actions.back }}</Link>
        </Button>
        <span v-else class="text-sm text-muted-foreground">{{
            actions.reassure
        }}</span>
        <span v-if="saved" class="setup-saved ml-auto" role="status">
            <Check class="size-4" /> {{ actions.saved }}
        </span>
        <Button type="submit" size="pill" :disabled="processing">
            <Spinner v-if="processing" />
            {{ isReview ? actions.finish : actions.next }}
        </Button>
    </div>
</template>
