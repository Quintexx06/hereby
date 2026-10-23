<script setup lang="ts">
import { ArrowUpRight } from '@lucide/vue';
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

<!--
    One design as its real first screens: the wide one in a browser window,
    the phone over its corner. The whole slide links to the sample site.
-->
<template>
    <div class="studio-shots">
        <figure class="studio-window">
            <div class="studio-bar" aria-hidden="true">
                <span class="studio-dots" />
                <span class="studio-url">{{ studio.url }}</span>
            </div>
            <img
                :src="`/images/designs/${design}.webp`"
                :alt="studio.shot(templates[design].name)"
                width="960"
                height="660"
                loading="lazy"
                decoding="async"
            />
        </figure>
        <figure class="studio-phone" aria-hidden="true">
            <img
                :src="`/images/designs/${design}-phone.webp`"
                alt=""
                width="390"
                height="844"
                loading="lazy"
                decoding="async"
            />
        </figure>
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
    </div>
</template>
