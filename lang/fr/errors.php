<?php

/* Error pages (resources/views/errors). */
return [
    'home' => 'Vers l’accueil',
    'reload' => 'Recharger',
    '403' => ['title' => 'L’accès n’est pas possible ici.', 'body' => 'Cette page appartient à un autre compte. Demandez le bon lien à la personne qui vous l’a envoyé.'],
    '404' => ['title' => 'Cette page n’existe pas.', 'body' => 'Le lien contient peut-être une faute de frappe. S’il s’agit d’une invitation personnelle, demandez le lien aux mariés.'],
    '419' => ['title' => 'La page est restée ouverte trop longtemps.', 'body' => 'Pour votre sécurité, elle a expiré. Rechargez-la et réessayez.'],
    '429' => ['title' => 'Un instant, s’il vous plaît.', 'body' => 'Beaucoup de demandes en peu de temps. Réessayez dans une minute.'],
    '500' => ['title' => 'Un problème est survenu.', 'body' => 'Nous avons été prévenus et nous nous en occupons. Réessayez dans un instant.'],
    '503' => ['title' => 'Nous revenons tout de suite.', 'body' => 'Hereby est en cours de mise à jour. Tout sera de retour dans quelques minutes.'],
];
