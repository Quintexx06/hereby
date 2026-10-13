<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { eveningPage as copy } from '@/content/evening';
import { show as publish } from '@/routes/weddings/publish';

defineProps<{ weddingId: number }>();
</script>

<!-- Without Atelier: what the evening would be, and where to get it. -->
<template>
    <div class="flex max-w-3xl flex-col items-start gap-5">
        <p class="publish-note">{{ copy.locked }}</p>
        <Link :href="publish(weddingId)" class="text-action hit-area">
            {{ copy.unlock }}
        </Link>
    </div>
</template>
