<script setup lang="ts">
const year = new Date().getFullYear();
</script>

<template>
    <footer class="page-container pb-10">
        <hr class="rule mb-6" />
        <div class="fine-print flex flex-col justify-between gap-2 sm:flex-row">
            <p>© {{ year }} Hereby. All rights reserved.</p>
            <p>Made in Switzerland.</p>
        </div>
    </footer>
</template>
