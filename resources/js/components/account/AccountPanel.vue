<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import { accountPanel as copy } from '@/content/account';
import { formatDate } from '@/lib/format';
import { privacy } from '@/routes/legal';

const page = usePage();
const account = computed(() => page.props.account);
const month = (iso: string) =>
    new Intl.DateTimeFormat('de-CH', { month: 'long', year: 'numeric' }).format(
        new Date(iso),
    );
</script>

<!--
    The account at a glance, on the night side: whose wedding, where its data
    lives and the day it disappears (product rule 4), and how sign-in is held.
-->
<template>
    <aside v-if="account" class="account-panel stage" :aria-label="copy.data">
        <header class="flex flex-col gap-1">
            <p class="font-display text-3xl leading-none font-semibold">
                {{ account.wedding?.couple_names ?? page.props.auth.user.name }}
            </p>
            <p class="text-sm text-muted-foreground">
                {{ copy.since(month(account.since)) }}
            </p>
        </header>

        <section class="flex flex-col gap-4">
            <h2 class="text-sm font-semibold">{{ copy.data }}</h2>
            <div class="account-fact">
                <HerebyIcon name="key" class="account-icon" />
                <p>
                    {{ copy.onlyYours }}
                    <span class="account-fact-hint">{{
                        copy.onlyYoursHint
                    }}</span>
                </p>
            </div>
            <div class="account-fact">
                <HerebyIcon name="calendar" class="account-icon" />
                <p>
                    {{
                        account.wedding?.deletes_on
                            ? copy.deletes(
                                  formatDate(
                                      account.wedding.deletes_on,
                                      'de-CH',
                                  ),
                              )
                            : copy.deletesOpen
                    }}
                    <span class="account-fact-hint">{{
                        copy.deletesHint
                    }}</span>
                </p>
            </div>
            <div v-if="account.wedding" class="account-fact">
                <HerebyIcon name="dinner" class="account-icon" />
                <p>
                    {{ copy.allergies }}
                    <span class="account-fact-hint">{{
                        copy.allergiesHint(account.wedding.guests)
                    }}</span>
                </p>
            </div>
        </section>

        <section class="flex flex-col gap-3">
            <h2 class="text-sm font-semibold">{{ copy.signIn }}</h2>
            <p class="flex flex-wrap gap-2 text-sm">
                <span
                    class="account-pill"
                    :data-on="account.two_factor || undefined"
                    >{{
                        account.two_factor
                            ? copy.twoFactorOn
                            : copy.twoFactorOff
                    }}</span
                >
                <span
                    class="account-pill"
                    :data-on="account.passkeys > 0 || undefined"
                    >{{ copy.passkeys(account.passkeys) }}</span
                >
            </p>
        </section>

        <Link
            :href="privacy()"
            class="link-underline hit-area self-start text-sm font-medium"
        >
            {{ copy.privacy }}
        </Link>
    </aside>
</template>
