<script setup lang="ts">
import { studio } from '@/content/landing';
import { templates } from '@/content/setup';
import type { WeddingTemplate } from '@/types';

const { eager = false } = defineProps<{
    design: WeddingTemplate;
    /** The picture in view at once (the setup's chosen design). */
    eager?: boolean;
}>();
</script>

<!--
    One design as its real first screens (public/images/designs): the wide
    one in a browser window, the phone over its lower right corner. Shown in
    the landing's studio and in the setup's look step.
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
                :loading="eager ? 'eager' : 'lazy'"
                decoding="async"
            />
        </figure>
        <figure class="studio-phone" aria-hidden="true">
            <img
                :src="`/images/designs/${design}-phone.webp`"
                alt=""
                width="390"
                height="844"
                :loading="eager ? 'eager' : 'lazy'"
                decoding="async"
            />
        </figure>
        <slot />
    </div>
</template>
