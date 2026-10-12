<!-- Atelier's first screen, small: the names in one big line, date, the turning seal. -->
<template>
    <span class="mini mini-atelier">
        <svg class="mini-seal" viewBox="0 0 100 100" aria-hidden="true">
            <defs>
                <path
                    id="mini-seal"
                    d="M50 50m-38 0a38 38 0 1 1 76 0a38 38 0 1 1-76 0"
                />
            </defs>
            <text>
                <textPath href="#mini-seal">
                    Save the date · 19. Juni 2027 ·
                </textPath>
            </text>
        </svg>
        <span class="mini-kicker">Wir heiraten</span>
        <span class="mini-names"
            >Anna<span class="mini-amp accent">&amp;</span>Luca</span
        >
        <span class="mini-atelier-foot">
            <span class="mini-date">19. Juni 2027</span>
            <span class="mini-pill">Noch 250 Tage</span>
        </span>
    </span>
</template>
