<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import InvitationMonogram from '@/components/invitation/InvitationMonogram.vue';
import { useTrans } from '@/composables/useTrans';
import type { Invitation } from '@/types';

/*
 * The site's own bar on wide screens, as premium wedding sites have it: the
 * couple's monogram, the chapters of this page, and the way to answer.
 * Over a dark opening it starts clear and turns to paper once the page
 * moves. Phones keep the reply bar at the thumb instead (StickyReply).
 */
const props = defineProps<{ invitation: Invitation; canReply: boolean }>();
const { t } = useTrans();

const links = computed(() => {
    const items: { href: string; label: string }[] = [];

    if (props.invitation.events.length) {
        items.push({
            href: '#inv-programme',
            label: t('invitation.programme_heading'),
        });
    }

    const practical = ['venue', 'travel', 'stay'];
    const seen = new Set<string>();
    for (const block of props.invitation.blocks) {
        const kind = practical.includes(block.type) ? 'practical' : block.type;
        if (seen.has(kind) || !['story', 'practical', 'faq'].includes(kind)) {
            continue;
        }
        seen.add(kind);
        items.push({
            href: `#block-${block.id}`,
            label: block.title ?? t(`invitation.blocks.${block.type}`),
        });
    }

    return items;
});

/* Salon opens on its wall, Arve on the landscape: the bar starts clear over them. */
const over = computed(() =>
    ['salon', 'arve'].includes(props.invitation.wedding.template),
);
const solid = ref(!over.value);

const onScroll = () => {
    solid.value = !over.value || window.scrollY > 40;
};

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});

onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));
</script>

<template>
    <nav
        class="inv-nav"
        :data-solid="solid"
        :aria-label="invitation.wedding.coupleNames"
    >
        <a href="#inv-letter" class="inv-nav-mark">
            <InvitationMonogram :couple="invitation.wedding.coupleNames" />
            <span class="sr-only">{{ invitation.wedding.coupleNames }}</span>
        </a>
        <ul class="inv-nav-links">
            <li v-for="link in links" :key="link.href">
                <a :href="link.href" class="inv-nav-link">{{ link.label }}</a>
            </li>
        </ul>
        <Link
            v-if="canReply && invitation.links"
            :href="invitation.links.reply"
            class="inv-nav-reply"
            >{{ t('rsvp.cta') }}</Link
        >
        <span v-else class="inv-nav-reply-gap" aria-hidden="true" />
    </nav>
</template>
