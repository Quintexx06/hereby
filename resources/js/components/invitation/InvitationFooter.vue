<script setup lang="ts">
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import type { Wedding } from '@/types';

const props = defineProps<{ wedding: Wedding }>();
const { t } = useTrans();

/* The names set large once more, the ampersand in the accent's italic. */
const names = computed(() => {
    const [first, ...rest] = props.wedding.coupleNames.split(/\s+&\s+/);

    return { first, second: rest.join(' & ') };
});
</script>

<!--
    The last lines of every site: who to ask (set by the couple on «Inhalte»),
    the one credit line (and the venue's, if it is a Showcase venue), and
    who looks after guests' data.
-->
<template>
    <footer class="inv-footer">
        <p class="inv-footer-couple">
            {{ names.first }}
            <template v-if="names.second">
                <span class="inv-footer-amp">&amp;</span> {{ names.second }}
            </template>
        </p>
        <address v-if="wedding.contact" class="inv-footer-contact">
            <span>{{ t('invitation.footer_questions') }}</span>
            <span v-if="wedding.contact.name" class="font-semibold">{{
                wedding.contact.name
            }}</span>
            <span class="inv-footer-links">
                <a
                    v-if="wedding.contact.email"
                    :href="`mailto:${wedding.contact.email}`"
                    class="inv-footer-link"
                    >{{ wedding.contact.email }}</a
                >
                <a
                    v-if="wedding.contact.phone"
                    :href="`tel:${wedding.contact.phone.replace(/[^+\d]/g, '')}`"
                    class="inv-footer-link"
                    >{{ wedding.contact.phone }}</a
                >
            </span>
        </address>
        <p v-if="wedding.venueCredit" class="caption">
            <a :href="wedding.venueCredit.url" class="inv-footer-link">{{
                t('invitation.footer_venue', {
                    venue: wedding.venueCredit.name,
                })
            }}</a>
        </p>
        <p class="caption">
            {{ t('common.credit') }} ·
            <a :href="wedding.privacyUrl" class="inv-footer-link">{{
                t('invitation.footer_privacy')
            }}</a>
        </p>
    </footer>
</template>
