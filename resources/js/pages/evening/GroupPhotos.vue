<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import EveningLocked from '@/components/evening/EveningLocked.vue';
import GroupPhotoRow from '@/components/evening/GroupPhotoRow.vue';
import type { GroupPhotoDraft } from '@/components/evening/GroupPhotoRow.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import SaveBar from '@/components/SaveBar.vue';
import { dashboardPage } from '@/content/dashboard';
import { eveningPage, groupPhotosPage as copy } from '@/content/evening';
import { firstError } from '@/lib/forms';
import { dashboard } from '@/routes';
import { show as evening } from '@/routes/weddings/evening';
import { update } from '@/routes/weddings/group-photos';

const props = defineProps<{
    wedding: { id: number; couple_names: string };
    unlocked: boolean;
    groups: (Omit<GroupPhotoDraft, 'place'> & { place: string | null })[];
    households: { id: number; name: string }[];
}>();

setLayoutProps({
    breadcrumbs: [
        { title: dashboardPage.title, href: dashboard() },
        { title: eveningPage.title, href: evening(props.wedding.id) },
        { title: copy.title, href: '' },
    ],
});

const form = useForm<{ groups: GroupPhotoDraft[] }>({
    groups: props.groups.map((group) => ({
        ...group,
        place: group.place ?? '',
    })),
});
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <EveningLocked v-if="!unlocked" :wedding-id="wedding.id" />

        <form
            v-else
            class="flex max-w-4xl flex-col gap-6"
            @submit.prevent="
                form.put(update.url(wedding.id), { preserveScroll: true })
            "
        >
            <p v-if="!households.length" class="publish-note">
                {{ copy.empty }}
            </p>
            <GroupPhotoRow
                v-for="(group, index) in form.groups"
                :key="group.id ?? `new-${index}`"
                v-model="form.groups[index]"
                :households="households"
                :error="firstError(form.errors, `groups.${index}`)"
                @remove="form.groups.splice(index, 1)"
            />
            <button
                type="button"
                class="text-action hit-area self-start"
                @click="
                    form.groups.push({
                        id: null,
                        title: '',
                        place: '',
                        household_ids: [],
                    })
                "
            >
                <Plus class="size-4" /> {{ copy.add }}
            </button>

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
