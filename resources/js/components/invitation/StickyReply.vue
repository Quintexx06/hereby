<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useTrans } from '@/composables/useTrans';

/*
 * On phones, once the letter (with its reply button) has scrolled away, the
 * way to answer stays at the bottom edge (rule 1), until the closing band
 * takes over. Hidden for households that answered.
 */
defineProps<{ href: string }>();
const { t } = useTrans();
const visible = ref(false);
const seen = new Map<string, boolean>();
let observer: IntersectionObserver | undefined;

onMounted(() => {
    const watched = ['inv-letter', 'inv-closing']
        .map((id) => document.getElementById(id))
        .filter((el): el is HTMLElement => el !== null);

    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) =>
            seen.set(entry.target.id, entry.isIntersecting),
        );
        const letterAbove =
            !seen.get('inv-letter') &&
            (document.getElementById('inv-letter')?.getBoundingClientRect()
                .bottom ?? 0) < 0;
        visible.value = letterAbove && !seen.get('inv-closing');
    });
    watched.forEach((el) => observer?.observe(el));
});
onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <Transition name="sticky-reply">
        <div v-if="visible" class="inv-sticky">
            <Link :href="href" class="inv-closing-button w-full">{{
                t('rsvp.cta')
            }}</Link>
        </div>
    </Transition>
</template>
