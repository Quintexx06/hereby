<script setup lang="ts">
import { ref } from 'vue';
import ImportPreview from '@/components/guests/import/ImportPreview.vue';
import ImportSource from '@/components/guests/import/ImportSource.vue';
import ManualHousehold from '@/components/guests/import/ManualHousehold.vue';
import { importCopy } from '@/content/guests';
import { useGuestImport } from '@/composables/useGuestImport';
import type { GuestsWedding } from '@/types';

const props = defineProps<{ wedding: GuestsWedding }>();
const emit = defineEmits<{ done: [] }>();

type Mode = keyof typeof importCopy.modes;
const mode = ref<Mode>('paste');
const guestImport = useGuestImport(props.wedding.id);
const { households, selected, reading, saving, error } = guestImport;

function finish(): void {
    guestImport.reset();
    emit('done');
}
</script>

<!-- Three ways in (paste, file, one by one), one preview before anything is saved. -->
<template>
    <div class="import-panel">
        <ImportPreview
            v-if="households"
            :households="households"
            :selected="selected"
            :saving="saving"
            @toggle="guestImport.toggle"
            @save="guestImport.save(finish)"
            @reset="guestImport.reset"
        />

        <template v-else>
            <div
                role="radiogroup"
                :aria-label="importCopy.title"
                class="flex flex-wrap gap-2"
            >
                <label
                    v-for="(label, value) in importCopy.modes"
                    :key="value"
                    class="chip h-10"
                >
                    <input
                        v-model="mode"
                        type="radio"
                        name="import_mode"
                        :value="value"
                        class="sr-only"
                    />
                    {{ label }}
                </label>
            </div>

            <ManualHousehold
                v-if="mode === 'manual'"
                :wedding="wedding"
                @saved="emit('done')"
            />
            <ImportSource
                v-else
                :mode="mode"
                :reading="reading"
                :error="error"
                @read="guestImport.read"
            />
        </template>
    </div>
</template>
