<?php

// «Der Abend» mails (spec 2026-10-13). Draft copy: needs native review.
return [
    'way_home' => [
        'subject' => 'In half an hour: :label at :time',
        'heading' => 'Time to think about getting home',
        'body' => ':label leaves at :time. Half an hour to go, so finish your drink slowly.',
        'from' => 'Departs from: :place',
        'cta' => 'Open the invitation',
    ],
    'group_photo' => [
        'subject' => 'Group photo: :title',
        'heading' => 'You’re up next',
        'body' => 'You’re needed now for the group photo “:title”.',
        'place' => 'Meet at: :place',
    ],
];
