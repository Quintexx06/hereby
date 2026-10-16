<?php

/* Error pages (resources/views/errors). */
return [
    'home' => 'Alla pagina iniziale',
    'reload' => 'Ricarica',
    '403' => ['title' => 'Qui non si può entrare.', 'body' => 'Questa pagina appartiene a un altro account. Chiedete il link giusto a chi ve l’ha inviato.'],
    '404' => ['title' => 'Questa pagina non esiste.', 'body' => 'Forse c’è un errore di battitura nel link. Se è un invito personale, chiedete il link agli sposi.'],
    '419' => ['title' => 'La pagina è rimasta aperta troppo a lungo.', 'body' => 'Per sicurezza è scaduta. Ricaricatela e riprovate.'],
    '429' => ['title' => 'Un attimo di pausa.', 'body' => 'Molte richieste in poco tempo. Riprovate tra un minuto.'],
    '500' => ['title' => 'Qualcosa è andato storto.', 'body' => 'Siamo già stati avvisati e ce ne occupiamo. Riprovate tra un momento.'],
    '503' => ['title' => 'Torniamo subito.', 'body' => 'Hereby è in aggiornamento. Tra pochi minuti tutto sarà di nuovo disponibile.'],
];
