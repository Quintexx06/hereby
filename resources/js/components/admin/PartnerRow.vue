<script setup lang="ts">
import { Check, Link2, Mail, Pencil } from '@lucide/vue';
import { computed, ref } from 'vue';
import { adminPartners as copy } from '@/content/partners';
import type { AdminPartner } from '@/types';

const props = defineProps<{ partner: AdminPartner }>();
const emit = defineEmits<{ edit: [] }>();
const copied = ref(false);

async function copyDemo(): Promise<void> {
    try {
        await navigator.clipboard.writeText(props.partner.demo_url ?? '');
        copied.value = true;
        setTimeout(() => (copied.value = false), 1800);
    } catch {
        copied.value = false;
    }
}

/* The pitch opens in the team's own mail app: nothing is sent from here. */
const pitch = computed(() => {
    const { partner } = props;

    if (!partner.demo_url || !partner.email) {
        return null;
    }

    const mail = copy.pitch(partner.name, partner.demo_url, partner.pitch_note);

    return `mailto:${partner.email}?subject=${encodeURIComponent(mail.subject)}&body=${encodeURIComponent(mail.body)}`;
});
</script>

<!-- One partner in the team's list: who, its code and sign-in, and for venues the demo. -->
<template>
    <li class="partner-row">
        <div class="flex min-w-0 flex-col gap-1">
            <p class="font-semibold">
                {{ partner.name }}
                <span class="font-normal text-muted-foreground">
                    · {{ partner.type_label }} ·
                    {{ copy.weddings(partner.weddings) }}
                </span>
            </p>
            <p class="caption">
                <span class="partner-code">{{ partner.code }}</span>
                · {{ partner.login ?? copy.noLogin }}
                <template v-if="partner.is_showcase">
                    · <span class="text-brand">{{ copy.showcase }}</span>
                </template>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <template v-if="partner.demo_url">
                <a
                    :href="partner.demo_url"
                    target="_blank"
                    rel="noopener"
                    class="witness-action"
                >
                    {{ copy.demoOpen }}
                </a>
                <button
                    type="button"
                    class="witness-action gap-2"
                    @click="copyDemo"
                >
                    <component
                        :is="copied ? Check : Link2"
                        class="size-4"
                        aria-hidden="true"
                    />
                    {{ copied ? copy.demoCopied : copy.demoCopy }}
                </button>
                <a v-if="pitch" :href="pitch" class="witness-action gap-2">
                    <Mail class="size-4" aria-hidden="true" />
                    {{ copy.pitchOpen }}
                </a>
            </template>
            <button
                type="button"
                class="witness-action gap-2"
                @click="emit('edit')"
            >
                <Pencil class="size-4" aria-hidden="true" />
                {{ copy.edit }}
            </button>
        </div>
    </li>
</template>
