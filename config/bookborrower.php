<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Borrowing Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the borrowing system behavior.
    |
    */

    'borrow_duration' => env('BORROW_DURATION', 14), // days
    'max_concurrent_borrows' => env('MAX_CONCURRENT_BORROWS', 3),
    'max_renewals' => env('MAX_RENEWALS', 2),

    /*
    |--------------------------------------------------------------------------
    | Fine Settings
    |--------------------------------------------------------------------------
    |
    | Configure the fine calculation system.
    |
    */

    'fine_amount_per_day' => env('FINE_AMOUNT_PER_DAY', 1.00), // in dollars
    'max_unpaid_fines' => env('MAX_UNPAID_FINES', 10.00), // maximum unpaid fines before blocking new borrows
];
