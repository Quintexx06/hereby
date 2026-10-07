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

];
