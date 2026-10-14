<script setup lang="ts">
import PremiumEvening from '@/components/marketing/PremiumEvening.vue';
import PremiumExtras from '@/components/marketing/PremiumExtras.vue';
import { premium } from '@/content/landing';
</script>

<!--
    The paid side, on night, as two products: the Atelier evening, shown
    moment by moment on a guest's phone, and the extras as tickets to add.
-->
<template>
    <section id="premium" class="stage overflow-hidden">
        <div class="page-container section flex flex-col gap-16 sm:gap-20">
            <div v-reveal class="flex max-w-4xl reveal flex-col gap-6">
                <h2 class="display">
                    {{ premium.titleLead }}
                    <span class="accent">{{ premium.titleAccent }}</span>
                </h2>
                <p class="lede">{{ premium.lede }}</p>
            </div>

            <PremiumEvening />
            <PremiumExtras />

            <p class="caption">{{ premium.note }}</p>
        </div>
    </section>
</template>
