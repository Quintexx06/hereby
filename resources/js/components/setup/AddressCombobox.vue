<script setup lang="ts">
import { onClickOutside } from '@vueuse/core';
import { computed, ref, useTemplateRef } from 'vue';
import { Input } from '@/components/ui/input';
import { venue } from '@/content/setup';
import { useAddressSearch } from '@/composables/useAddressSearch';
import { useSetupForm } from '@/composables/useSetupForm';
import type { SwissAddress } from '@/types/setup';

const emit = defineEmits<{ select: [SwissAddress | null]; manual: [] }>();

const form = useSetupForm();
const { results, loading, lookup } = useAddressSearch();
const query = ref('');
const open = ref(false);
const active = ref(-1);
const root = useTemplateRef<HTMLElement>('root');

const chosen = computed(() =>
    form.venue_reference
        ? `${form.venue_address}, ${form.venue_postcode} ${form.venue_town}`
        : null,
);

onClickOutside(root, () => (open.value = false));

function onInput(): void {
    open.value = true;
    active.value = -1;
    lookup(query.value);
}

function pick(address: SwissAddress): void {
    emit('select', address);
    open.value = false;
    query.value = '';
}

function onKeydown(event: KeyboardEvent): void {
    const count = results.value.length;

    if (event.key === 'ArrowDown' && count) {
        event.preventDefault();
        open.value = true;
        active.value = (active.value + 1) % count;
    } else if (event.key === 'ArrowUp' && count) {
        event.preventDefault();
        active.value = (active.value - 1 + count) % count;
    } else if (event.key === 'Enter' && open.value && active.value >= 0) {
        event.preventDefault();
        pick(results.value[active.value]);
    } else if (event.key === 'Escape') {
        open.value = false;
    }
}
</script>

<template>
    <div class="field">
        <label for="venue_address_search" class="field-label">{{
            venue.address
        }}</label>

        <div v-if="chosen" class="address-card">
            <span class="flex flex-col gap-0.5">
                <span class="font-medium">{{ chosen }}</span>
                <span class="text-sm text-success">{{ venue.official }}</span>
            </span>
            <button
                type="button"
                class="link-underline text-sm"
                @click="emit('select', null)"
            >
                {{ venue.clear }}
            </button>
        </div>

        <div v-else ref="root" class="relative">
            <Input
                id="venue_address_search"
                v-model="query"
                role="combobox"
                class="h-11"
                autocomplete="off"
                aria-autocomplete="list"
                aria-controls="venue_address_results"
                :aria-expanded="open && results.length > 0"
                :aria-activedescendant="
                    active >= 0 ? `venue_address_option_${active}` : undefined
                "
                :placeholder="venue.addressPlaceholder"
                @input="onInput"
                @keydown="onKeydown"
                @focus="open = results.length > 0"
            />
            <ul
                v-show="open && results.length > 0"
                id="venue_address_results"
                role="listbox"
                class="combobox-list"
            >
                <li
                    v-for="(address, index) in results"
                    :id="`venue_address_option_${index}`"
                    :key="address.reference || address.label"
                    role="option"
                    class="combobox-option"
                    :aria-selected="index === active"
                    @mousedown.prevent="pick(address)"
                    @mousemove="active = index"
                >
                    <span class="font-medium">{{ address.street }}</span>
                    <span class="text-muted-foreground"
                        >{{ address.postcode }} {{ address.town }}</span
                    >
                </li>
            </ul>
            <p class="field-hint mt-2" aria-live="polite">
                <template v-if="loading">{{ venue.searching }}</template>
                <template
                    v-else-if="query.trim().length >= 3 && results.length === 0"
                    >{{ venue.noResults }}</template
                >
            </p>
            <button
                type="button"
                class="link-underline self-start text-sm"
                @click="emit('manual')"
            >
                {{ venue.manual }}
            </button>
        </div>
    </div>
</template>
