<script setup lang="ts">
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import PreviewPhone from '@/components/marketing/PreviewPhone.vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import SectionHeading from '@/components/marketing/SectionHeading.vue';
import { guests } from '@/content/landing';
</script>

<template>
    <section id="gaeste" class="bg-muted/60">
        <div class="page-container section guests-grid">
            <div class="flex flex-col gap-12">
                <SectionHeading
                    v-reveal
                    class="reveal"
                    :title="guests.title"
                    :lede="guests.lede"
                />
                <ul class="border-b border-rule">
                    <li
                        v-for="(point, index) in guests.points"
                        :key="point.title"
                        v-reveal="index * 80"
                        class="point-row reveal"
                    >
                        <HerebyIcon
                            :name="point.icon"
                            class="row-span-2 sm:row-span-1"
                        />
                        <h3 class="title text-xl sm:text-2xl">
                            {{ point.title }}
                        </h3>
                        <p class="text-muted-foreground">{{ point.body }}</p>
                    </li>
                </ul>
            </div>

            <div class="guests-visual">
                <ResponsivePhoto
                    :photo="guests.photo"
                    sizes="(min-width: 1024px) 40vw, 90vw"
                    class="guests-photo"
                />
                <div class="guests-phone">
                    <PreviewPhone :label="guests.previewLabel" />
                </div>
            </div>
        </div>
    </section>
</template>
