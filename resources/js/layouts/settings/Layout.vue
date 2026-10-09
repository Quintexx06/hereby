<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AccountPanel from '@/components/account/AccountPanel.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { account } from '@/content/account';
import { toUrl } from '@/lib/utils';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';

const items = [
    { title: account.nav.profile, href: editProfile() },
    { title: account.nav.security, href: editSecurity() },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <div class="app-page gap-10">
        <header class="flex flex-col gap-2">
            <h1 class="app-title">{{ account.title }}</h1>
            <p class="text-muted-foreground">{{ account.lede }}</p>
        </header>

        <nav class="settings-tabs" :aria-label="account.navLabel">
            <Link
                v-for="item in items"
                :key="toUrl(item.href)"
                :href="item.href"
                class="settings-tab"
                :aria-current="
                    isCurrentOrParentUrl(item.href) ? 'page' : undefined
                "
            >
                {{ item.title }}
            </Link>
        </nav>

        <div class="account-grid">
            <div class="flex max-w-xl flex-col gap-14">
                <slot />
            </div>
            <AccountPanel />
        </div>
    </div>
</template>
