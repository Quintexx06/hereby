<script setup lang="ts">
import StruckRow from '@/components/marketing/StruckRow.vue';
import { struck } from '@/content/landing';
</script>

<!-- "Hereby" means "hiermit": the things we leave out, struck through like a contract. -->
<template>
    <section class="page-container section flex flex-col gap-14">
        <div v-reveal class="flex reveal flex-col gap-6">
            <h2 class="display">
                {{ struck.titleLead }}
                <span class="accent">{{ struck.titleAccent }}</span>
            </h2>
            <p class="lede">{{ struck.lede }}</p>
        </div>
        <ul class="border-b border-rule">
            <StruckRow
                v-for="(item, index) in struck.items"
                :key="item.word"
                v-bind="item"
                :index="index"
            />
        </ul>
    </section>
</template>
