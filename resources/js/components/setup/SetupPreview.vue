<script setup lang="ts">
import { computed } from 'vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import InvitationPreview from '@/components/marketing/InvitationPreview.vue';
import { themes } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import { previewFromForm } from '@/lib/setup';

const form = useSetupForm();
const household = computed(() => previewFromForm(form));
</script>

<!--
    The couple's invitation, live: every answer shows up on the phone as it
    is typed, in the theme they pick. This is the moment the setup is for.
-->
<template>
    <aside class="setup-preview stage" aria-label="Vorschau eurer Einladung">
        <div class="phone-frame">
            <WeddingThemeScope
                :theme="form.theme"
                lang="de-CH"
                :fill="false"
                class="overflow-hidden rounded-[2.1rem]"
            >
                <InvitationPreview :household="household" />
            </WeddingThemeScope>
        </div>
        <p class="text-sm text-muted-foreground">
            So sieht Familie Meier eure Einladung, im Look
            {{ themes[form.theme].name }}.
        </p>
    </aside>
</template>
