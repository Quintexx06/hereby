<script setup lang="ts">
import { ArrowUpRight } from '@lucide/vue';
import DesignShots from '@/components/marketing/DesignShots.vue';
import { studio } from '@/content/landing';
import { templates } from '@/content/setup';
import { show as sample } from '@/routes/samples';
import type { WeddingTemplate } from '@/types';

defineProps<{ design: WeddingTemplate; active: boolean }>();
const emit = defineEmits<{ step: [] }>();

/* A window at the side comes to the front first; the front one opens live. */
function open(event: MouseEvent, active: boolean): void {
    if (!active) {
        event.preventDefault();
        emit('step');
    }
}
</script>

<!-- One design in the studio carousel; the whole slide links to the sample site. -->
<template>
    <DesignShots :design="design">
        <a
            :href="sample.url(design)"
            target="_blank"
            rel="noopener"
            class="studio-open"
            :tabindex="active ? 0 : -1"
            :aria-label="`${templates[design].name}: ${studio.live}`"
            @click="open($event, active)"
        >
            <span class="studio-open-badge">
                {{ studio.live }} <ArrowUpRight class="size-4" />
            </span>
        </a>
    </DesignShots>
</template>
