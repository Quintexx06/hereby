<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import LookStyles from '@/components/setup/look/LookStyles.vue';
import LookThemes from '@/components/setup/look/LookThemes.vue';
import { look } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import type { ThemeSuggestion } from '@/types/setup';

defineProps<{
    suggestion: ThemeSuggestion | null;
}>();

const form = useSetupForm();
</script>

<!-- The look in three parts: colour world, style directions, their own idea. -->
<template>
    <div class="flex flex-col gap-12">
        <LookThemes :suggestion="suggestion" />
        <LookStyles />

        <div class="look-section">
            <div class="look-legend">
                <label for="look_wishes" class="app-section-title">{{
                    look.wishes
                }}</label>
                <span class="field-hint">{{ look.wishesHint }}</span>
            </div>
            <textarea
                id="look_wishes"
                v-model="form.look_wishes"
                class="textarea min-h-32"
                maxlength="2000"
                :placeholder="look.wishesPlaceholder"
            />
            <InputError :message="form.errors.look_wishes" />
        </div>
    </div>
</template>
