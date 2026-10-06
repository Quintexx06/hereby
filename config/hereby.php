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

];
