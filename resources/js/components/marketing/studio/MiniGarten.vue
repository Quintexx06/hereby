<script setup lang="ts">
import GartenBranch from '@/components/invitation/templates/GartenBranch.vue';
</script>

<!-- Garten's first screen, small: the arch, the branches drawing themselves, petals. -->
<template>
    <span class="mini mini-garten">
        <span class="garten-petals" aria-hidden="true">
            <span
                v-for="petal in 6"
                :key="petal"
                :style="{ '--p': petal * 1.6 }"
            />
        </span>
        <GartenBranch side="left" />
        <span class="mini-arch">
            <span class="text-[0.6rem] text-muted-foreground"
                >19. Juni 2027</span
            >
            <span class="mini-names text-[2.1rem]"
                >Anna<span class="mini-amp accent">&amp;</span>Luca</span
            >
            <span class="text-[0.7rem] font-medium">heiraten</span>
        </span>
        <GartenBranch side="right" />
    </span>
</template>
