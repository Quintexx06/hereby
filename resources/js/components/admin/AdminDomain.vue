<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { domain as connectDomain } from '@/routes/admin/weddings';
import type { AdminWedding } from '@/types';

/* The couple's own domain: connect it once DNS and TLS work. */
const props = defineProps<{ wedding: AdminWedding }>();

function toggle(): void {
    router.patch(
        connectDomain.url(props.wedding.id),
        { connected: !props.wedding.domain_connected },
        { preserveScroll: true },
    );
}
</script>

<template>
    <button
        v-if="wedding.domain"
        type="button"
        class="admin-plan"
        :data-published="wedding.domain_connected"
        :title="
            wedding.domain_connected
                ? 'Domain trennen'
                : 'Als verbunden markieren'
        "
        @click="toggle"
    >
        {{ wedding.domain }} ·
        {{ wedding.domain_connected ? 'verbunden' : 'verbinden' }}
    </button>
</template>
