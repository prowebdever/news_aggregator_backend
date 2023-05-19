<?php

namespace App\NewsFeed;

use App\Models\News;
use App\Models\Source;
use Illuminate\Support\Facades\Http;

class TheGuardianNewsApiFeeder extends NewsFeeder {

    public static function init()
    {
        // Get the configuration for The Guardian news source
        $config = config('news-sources.theguardian');

        // Create a new instance of the class
        $instance = new self;

        // Set the instance with the configuration and source ID
        parent::set($instance, $config, News::THE_GUARDIAN_API_SOURCE_ID);

        // Return the instance
        return $instance;
    }

    public function fetch($perPage = 10)
    {
        // Set the query parameters for the API request
        $queryParams = [
            'pageSize'              => $perPage,
            'page'                  => 1,
            $this->apiKeyParamName  => $this->apiKey,
        ];

        // Send the API request and get the response
        $response = Http::get($this->apiUrl, $queryParams);

        // Convert the response to JSON
        $jsonResponse = $response->json();

        // Check if the response is successful and has the expected status
        if($response->ok() && $jsonResponse['response']['status'] == 'ok'){

            // Save each news item into the news table
            foreach($jsonResponse['response']['results'] as $newsItem){
                parent::saveNews(
                    $newsItem['webTitle'],
                    $newsItem['webTitle'],
                    $newsItem['pillarName'],
                    null,
                    'https://placehold.co/400x200?text=No+Image',
                    $newsItem['webUrl'],
                    $newsItem['webPublicationDate'],
                    News::THE_GUARDIAN_API_SOURCE_ID
                );
            }

            // Return a success response
            return parent::successResponse();
        }

        // Return a failed response with the JSON response
        return parent::failedResponse($jsonResponse);
    }
}
