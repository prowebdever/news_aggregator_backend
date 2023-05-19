<?php

namespace App\NewsFeed;

use App\Models\News;
use Illuminate\Support\Facades\Http;

class NewYorkTimesNewsApiFeeder extends NewsFeeder {

    /**
     * Initialize a NewYorkTimesNewsApiFeeder instance with configuration settings.
     *
     * @return NewYorkTimesNewsApiFeeder The initialized NewYorkTimesNewsApiFeeder instance.
     */
    public static function init()
    {
        $config = config('news-sources.newyorktimes');
        $instance = new self;
        parent::set($instance, $config, News::NEW_YORK_TIMES_API_SOURCE_ID);
        return $instance;
    }

    /**
     * Fetch news articles from the New York Times API and save them to the database.
     *
     * @return array The response from the API.
     */
    public function fetch()
    {
        $queryParams = [
            'page'                  => 0,
            $this->apiKeyParamName  => $this->apiKey,
        ];

        $response = Http::get($this->apiUrl, $queryParams);
        $jsonResponse = $response->json();
        if($response->ok() && strtolower($jsonResponse['status']) == 'ok'){
            // Save news articles into the news table
            foreach($jsonResponse['response']['docs'] as $item){
                parent::saveNews(
                    $item['abstract'],
                    $item['lead_paragraph'],
                    $item['section_name'],
                    isset($item['byline']) ? $item['byline']['person'][0]['firstname'] . ' ' . $item['byline']['person'][0]['lastname'] : null,
                    isset($item['multimedia']) && count($item['multimedia']) > 0 ? $this->extractImageFromMultimedia($item['multimedia']) : 'https://placehold.co/400x200?text=No+Image',
                    $item['web_url'],
                    $item['pub_date'],
                    News::NEW_YORK_TIMES_API_SOURCE_ID
                );
            }
            return parent::successResponse();
        }
        return parent::failedResponse($jsonResponse);
    }

    /**
     * Extract the URL of the first image from a multimedia array.
     *
     * @param array $multimediaArray The multimedia array to extract the image URL from.
     * @return string The URL of the first image in the multimedia array.
     */
    public function extractImageFromMultimedia($multimediaArray)
    {
        return 'https://www.nytimes.com/' . $multimediaArray[0]['url'];
    }
}
