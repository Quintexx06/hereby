<script setup lang="ts">
/* What a guest sees on the phone at each moment of the night (illustration). */
defineProps<{ moment: string }>();
</script>

<template>
    <div class="phone-screen premium-screen" data-theme="salon">
        <p class="text-[0.8125rem] text-muted-foreground">
            Für Heidi, Peter und Lina
        </p>

        <template v-if="moment === 'groupPhoto'">
            <div class="premium-call">
                <p class="font-display text-lg font-semibold">
                    Gruppenfoto: Ihr seid dran
                </p>
                <p class="font-semibold">Familie Meier mit dem Brautpaar</p>
                <p class="text-sm opacity-80">Treffpunkt: Beim Steg</p>
            </div>
            <p class="premium-screen-title">Anna &amp; Luca heiraten</p>
            <p class="text-sm text-muted-foreground">Samstag, 19. Juni 2027</p>
        </template>

        <template v-else-if="moment === 'seating'">
            <p class="premium-screen-title">Euer Tisch</p>
            <div class="premium-mini-card">
                <p class="font-display text-lg font-semibold">Tisch Rigi</p>
                <p class="text-xs text-muted-foreground">
                    Ihr: Heidi, Peter, Lina
                </p>
                <p class="mt-2 text-xs font-semibold text-brand">
                    Mit euch am Tisch
                </p>
                <p class="premium-mate">
                    <b>Camille</b> Lucas Studienfreund. Fragt ihn nach dem Velo.
                </p>
                <p class="premium-mate"><b>Emma</b> Aus London angereist.</p>
                <p class="premium-mate">
                    <b>Marco</b> Kennt Anna seit dem Kindergarten.
                </p>
            </div>
        </template>

        <template v-else-if="moment === 'programme'">
            <p class="premium-screen-title">Für die Trauzeugen</p>
            <p class="text-xs text-muted-foreground">
                23 von 45 Minuten verplant
            </p>
            <div class="mt-3 flex flex-col">
                <p class="premium-row">
                    <span><b>Rede der Schwester</b><br />Heidi · 8 Min.</span
                    ><span class="premium-chip" data-on="true">Angenommen</span>
                </p>
                <p class="premium-row">
                    <span><b>Das Schuhquiz</b><br />Marco · 15 Min.</span
                    ><span class="premium-chip" data-on="true">Angenommen</span>
                </p>
                <p class="premium-row">
                    <span><b>Lied der Cousins</b><br />Lina · 5 Min.</span
                    ><span class="premium-chip">Offen</span>
                </p>
            </div>
        </template>

        <template v-else>
            <p class="premium-screen-title">Euer Heimweg</p>
            <div class="premium-mini-card">
                <p class="premium-row">
                    <span
                        ><b class="text-brand">23:30</b><br /><b
                            >Shuttle nach Zürich</b
                        ></span
                    >
                </p>
                <p class="premium-row">
                    <span
                        ><b class="text-brand">00:40</b><br /><b
                            >Letztes Schiff nach Luzern</b
                        ><br />ab Steg Vitznau</span
                    ><span class="premium-chip" data-on="true">in 28 Min.</span>
                </p>
            </div>
        </template>

        <span class="phone-action mt-auto">Zur Einladung</span>
    </div>
</template>
