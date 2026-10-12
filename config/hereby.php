<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Guest data retention
    |--------------------------------------------------------------------------
    |
    | Weddings, and with them every household, guest and answer, are deleted
    | this many months after the wedding date (see ADR 0007).
    |
    */

    'retention_months' => (int) env('HEREBY_RETENTION_MONTHS', 12),

    /*
    |--------------------------------------------------------------------------
    | Team inbox
    |--------------------------------------------------------------------------
    |
    | Questions from the landing page ("Fragt uns") are mailed here. Leave it
    | empty to only store them in the `inquiries` table.
    |
    */

    'inbox' => env('HEREBY_INBOX'),

    /*
    |--------------------------------------------------------------------------
    | Plans
    |--------------------------------------------------------------------------
    |
    | One-time prices in Rappen. Building a site is free; a plan sends the
    | personal links (Signature) and adds the memories (Atelier). Couture
    | starts at this price and is agreed in person.
    |
    */

    'plans' => [
        'signature' => (int) env('HEREBY_PRICE_SIGNATURE', 29000),
        'atelier' => (int) env('HEREBY_PRICE_ATELIER', 59000),
        'couture' => (int) env('HEREBY_PRICE_COUTURE', 149000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Memories
    |--------------------------------------------------------------------------
    |
    | Guest photos (Atelier and up): the largest photo a guest may send, in
    | kilobytes, and how many one wedding may hold. The server's upload limit
    | (upload_max_filesize, post_max_size) must allow the former.
    |
    */

    'memories' => [
        'max_kilobytes' => (int) env('HEREBY_MEMORIES_MAX_KILOBYTES', 20480),
        'max_photos' => (int) env('HEREBY_MEMORIES_MAX_PHOTOS', 3000),
    ],

];
