<script setup lang="ts">
import { DatePicker } from '@/components/ui/date-picker';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PageMasthead from '@/components/PageMasthead.vue';
import PartnerCode from '@/components/partners/PartnerCode.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { tourPreview as copy } from '@/content/partners';
import { studio } from '@/content/landing';
import { preview } from '@/routes/partner';

defineProps<{ venue: string; code: string }>();

defineOptions({
    layout: { breadcrumbs: [{ title: copy.title, href: '' }] },
});

/* Six months ahead, a Saturday-ish default the couple will change anyway. */
const inSixMonths = new Date(Date.now() + 182 * 86_400_000)
    .toISOString()
    .slice(0, 10);
const date = ref(inSixMonths);
</script>

<!--
    On the tour: two names and a date open the couple's site at this venue
    in a new tab. A plain GET form, so nothing is stored.
-->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <form
            :action="preview.url()"
            method="get"
            target="_blank"
            class="partner-tour"
        >
            <div class="grid gap-2">
                <label for="erste" class="field-label">{{ copy.first }}</label>
                <Input id="erste" name="erste" placeholder="Lea" required />
            </div>
            <div class="grid gap-2">
                <label for="zweite" class="field-label">{{
                    copy.second
                }}</label>
                <Input id="zweite" name="zweite" placeholder="Jonas" required />
            </div>
            <div class="grid gap-2">
                <label for="datum" class="field-label">{{ copy.date }}</label>
                <DatePicker id="datum" v-model="date" name="datum" />
            </div>
            <fieldset class="chip-row sm:col-span-3">
                <legend class="field-label mb-2">{{ copy.template }}</legend>
                <label
                    v-for="(template, index) in studio.templates"
                    :key="template.slug"
                    class="chip"
                >
                    <input
                        type="radio"
                        name="vorlage"
                        :value="template.slug"
                        :checked="index === 0"
                        class="sr-only"
                    />
                    {{ template.name }}
                </label>
            </fieldset>
            <Button type="submit" size="pill" class="sm:col-span-3 sm:w-fit">
                {{ copy.show }}
            </Button>
        </form>

        <div class="flex flex-col gap-3">
            <p class="text-muted-foreground">{{ copy.handover }}</p>
            <PartnerCode :code="code" />
        </div>
    </div>
</template>
