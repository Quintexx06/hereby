<?php

/* Error pages (resources/views/errors). */
return [
    'home' => 'To the home page',
    'reload' => 'Reload',
    '403' => ['title' => 'You can\'t go further here.', 'body' => 'This page belongs to another account. Ask whoever sent you the link for the right one.'],
    '404' => ['title' => 'This page doesn\'t exist.', 'body' => 'There may be a typo in the link. If it\'s a personal invitation, ask the couple for the link.'],
    '419' => ['title' => 'This page was open too long.', 'body' => 'For your security it has expired. Reload it and try again.'],
    '429' => ['title' => 'Take a breath.', 'body' => 'That was a lot of requests in a short time. Try again in a minute.'],
    '500' => ['title' => 'Something went wrong.', 'body' => 'We\'ve been notified and are looking into it. Try again in a moment.'],
    '503' => ['title' => 'We\'ll be right back.', 'body' => 'Hereby is being updated. Everything will be back in a few minutes.'],
];
