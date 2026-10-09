<script setup lang="ts">
import { Plus, X } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { rsvpSettings as copy } from '@/content/rsvp';

/* The couple's menus in their own words; each keeps its key when renamed. */
defineProps<{ error?: string }>();
const menus = defineModel<string[]>('menus', { required: true });
const keys = defineModel<(string | null)[]>('keys', { required: true });
const childrenMenu = defineModel<boolean>('childrenMenu', { required: true });

function addMenu(): void {
    menus.value.push('');
    keys.value.push(null);
}

function removeMenu(index: number): void {
    menus.value.splice(index, 1);
    keys.value.splice(index, 1);
}
</script>

<template>
    <section class="settings-section">
        <div class="flex flex-col gap-1">
            <h2 class="app-section-title">{{ copy.menus }}</h2>
            <p class="field-hint">{{ copy.menusHint }}</p>
        </div>
        <div
            v-for="(_, index) in menus"
            :key="index"
            class="flex items-end gap-2"
        >
            <div class="field flex-1">
                <label :for="`menu-${index}`" class="sr-only">{{
                    copy.menuLabel(index)
                }}</label>
                <Input
                    :id="`menu-${index}`"
                    v-model="menus[index]"
                    maxlength="80"
                    :placeholder="copy.menuPlaceholder"
                />
            </div>
            <button
                type="button"
                class="icon-button"
                :aria-label="copy.removeMenu"
                @click="removeMenu(index)"
            >
                <X class="size-4" />
            </button>
        </div>
        <button
            v-if="menus.length < 4"
            type="button"
            class="link-underline hit-area inline-flex items-center gap-1.5 self-start text-sm"
            @click="addMenu"
        >
            <Plus class="size-4" /> {{ copy.addMenu }}
        </button>
        <InputError :message="error" />
        <label class="check-label">
            <input v-model="childrenMenu" type="checkbox" class="checkbox" />
            {{ copy.childrenMenu }}
        </label>
    </section>
</template>
