<?php

return [
    'api_key' => env('API_KEY', 'hotel-guest-search-2026'),

    'guest_search' => [
        'per_page' => env('GUEST_SEARCH_PER_PAGE', 15),
        'max_per_page' => 100,
    ],
];
