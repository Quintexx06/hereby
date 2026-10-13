<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';
import type { EveningTable } from '@/types';

defineProps<{ tables: EveningTable[] }>();

const { t } = useTrans();
</script>

<!-- «Euer Tisch»: who sits with the household, each with the couple's line. -->
<template>
    <section class="inv-section">
        <h2 class="inv-heading">
            {{ t('invitation.evening.tables_heading') }}
        </h2>
        <article v-for="table in tables" :key="table.name" class="evening-card">
            <p class="evening-card-title">
                {{ t('invitation.evening.table', { name: table.name }) }}
            </p>
            <p class="text-sm text-muted-foreground">
                {{ t('invitation.evening.you') }}: {{ table.yours.join(', ') }}
            </p>
            <template v-if="table.mates.length">
                <p class="evening-card-label">
                    {{ t('invitation.evening.mates') }}
                </p>
                <ul class="evening-mates">
                    <li
                        v-for="mate in table.mates"
                        :key="mate.name + mate.note"
                    >
                        <span class="font-semibold">{{ mate.name }}</span>
                        <span v-if="mate.note" class="text-muted-foreground">
                            {{ mate.note }}</span
                        >
                    </li>
                </ul>
            </template>
        </article>
    </section>
</template>
