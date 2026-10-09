<script setup lang="ts">
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import { adminWelcome as copy } from '@/content/admin';
import { landingPhotos } from '@/content/landing-photos';

const open = defineModel<boolean>('open', { required: true });
const emit = defineEmits<{ finish: [] }>();

const index = ref(0);
const slide = computed(() => copy.slides[index.value]);
const photo = computed(() => landingPhotos[slide.value.photo]);
const isLast = computed(() => index.value === copy.slides.length - 1);

function finish(): void {
    open.value = false;
    index.value = 0;
    emit('finish');
}

function next(): void {
    if (isLast.value) {
        finish();
    } else {
        index.value++;
    }
}
</script>

<!-- First-visit tour: three photo slides, skippable, re-opened from the hero. -->
<template>
    <Dialog
        :open="open"
        @update:open="(value) => (value ? (open = true) : finish())"
    >
        <DialogContent class="gap-0 overflow-hidden p-0 sm:max-w-md">
            <Transition name="welcome-slide" mode="out-in">
                <img
                    :key="photo.src"
                    :src="photo.src"
                    :alt="photo.alt"
                    class="admin-welcome-photo"
                />
            </Transition>
            <div class="flex flex-col gap-2 p-6 pb-0">
                <DialogTitle class="app-section-title text-2xl">
                    {{ slide.title }}
                </DialogTitle>
                <DialogDescription class="text-pretty">
                    {{ slide.text }}
                </DialogDescription>
            </div>
            <div class="flex items-center justify-between gap-4 p-6">
                <div class="flex gap-1.5" aria-hidden="true">
                    <span
                        v-for="(_, dot) in copy.slides"
                        :key="dot"
                        class="admin-welcome-dot"
                        :data-active="dot === index"
                    />
                </div>
                <div class="flex gap-2">
                    <Button
                        v-if="index > 0"
                        variant="ghost"
                        class="rounded-full"
                        @click="index--"
                    >
                        {{ copy.back }}
                    </Button>
                    <Button
                        v-else
                        variant="ghost"
                        class="rounded-full"
                        @click="finish"
                    >
                        {{ copy.skip }}
                    </Button>
                    <Button class="rounded-full" @click="next">
                        {{ isLast ? copy.done : copy.next }}
                    </Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
