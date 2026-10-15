<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import PageMasthead from '@/components/PageMasthead.vue';
import InputError from '@/components/InputError.vue';
import SaveBar from '@/components/SaveBar.vue';
import VenuePhotos from '@/components/partners/VenuePhotos.vue';
import { Input } from '@/components/ui/input';
import { partnerProfile as copy } from '@/content/partners';
import { update } from '@/routes/partner/profile';

type Profile = Record<keyof typeof copy.fields, string | number | null>;

const props = defineProps<{
    isVenue: boolean;
    profile: Profile;
    photos: { id: number; url: string }[];
}>();

defineOptions({
    layout: { breadcrumbs: [{ title: 'Profil', href: '' }] },
});

const form = useForm<Profile>({ ...props.profile });

const contact = ['name', 'email', 'phone', 'website'] as const;
const place = ['address', 'postcode', 'town', 'capacity'] as const;
const texts = ['about', 'travel', 'stay'] as const;
const inputType = {
    email: 'email',
    phone: 'tel',
    website: 'url',
    capacity: 'number',
} as Record<string, string>;
</script>

<!-- Entered once: contact for every partner; place and texts for venues. -->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-8">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <VenuePhotos v-if="isVenue" :photos="photos" />

        <form
            class="flex max-w-3xl flex-col gap-8"
            @submit.prevent="form.put(update.url(), { preserveScroll: true })"
        >
            <div class="partner-fields">
                <div
                    v-for="field in isVenue ? [...contact, ...place] : contact"
                    :key="field"
                    class="grid gap-2"
                >
                    <label :for="field" class="field-label">{{
                        copy.fields[field]
                    }}</label>
                    <Input
                        :id="field"
                        v-model="form[field]"
                        :type="inputType[field] ?? 'text'"
                    />
                    <InputError :message="form.errors[field]" />
                </div>
            </div>

            <template v-if="isVenue">
                <div v-for="field in texts" :key="field" class="grid gap-2">
                    <label :for="field" class="field-label">{{
                        copy.fields[field]
                    }}</label>
                    <p class="field-hint">{{ copy.hints[field] }}</p>
                    <textarea
                        :id="field"
                        v-model="form[field] as string"
                        rows="4"
                        class="textarea"
                    />
                    <InputError :message="form.errors[field]" />
                </div>
            </template>

            <SaveBar
                :processing="form.processing"
                :dirty="form.isDirty"
                :saved="form.recentlySuccessful"
                :label="copy.save"
                :saved-label="copy.saved"
            />
        </form>
    </div>
</template>
