<?php

return [

    // Configuration for News API
    'newsapi'   => [
        'api_url'           => 'https://newsapi.org/v2/top-headlines',
        'api_key'           => env('NEWSAPI_API_KEY', 'e6c1e09b72374a06a34697932bae5493'),
        'api_parameter_name'=> 'apiKey',
        'other_parameters'  => ['country' => 'us'],
    ],

    // Configuration for The Guardian API
    'theguardian'   => [
        'api_url'           => 'https://content.guardianapis.com/search',
        'api_key'           => env('THEGUARDIAN_API_KEY', 'b047d2a9-4bd7-4702-b4cf-0a9a4cd53487'),
        'api_parameter_name'=> 'api-key',
    ],

    // Configuration for New York Times API
    'newyorktimes'   => [
        'api_url'           => 'https://api.nytimes.com/svc/search/v2/articlesearch.json',
        'api_key'           => env('NEWYORKTIMES_API_KEY', 'NzwVvIUBCfWQBV8sPV6zX5w5MycwfCln'),
        'api_parameter_name'=> 'api-key',
    ]

];
