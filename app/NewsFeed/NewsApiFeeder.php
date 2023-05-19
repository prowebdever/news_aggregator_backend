<?php

namespace App\NewsFeed;

use App\Models\News;
use Illuminate\Support\Facades\Http;

class NewsApiFeeder extends NewsFeeder
{
    /**
     * Initialize the NewsApiFeeder instance with configuration settings.
     *
     * @return NewsApiFeeder
     */
    public static function init()
    {
        $config = config('news-sources.newsapi');
        $instance = new self;
        parent::set($instance, $config, News::NEWS_API_SOURCE_ID);
        $instance->otherParams = $config['other_parameters'];
        return $instance;
    }

    /**
     * Fetch news articles from the News API.
     *
     * @param int $perPage The number of articles to fetch per page.
     * @return array The response from the NewsFeeder parent class.
     */
    public function fetch($perPage = 10)
    {
        $categories = ['general', 'business', 'sports', 'science', 'health', 'entertainment', 'technology'];

        $queryParams = array_merge([
            'category'              => '',
            'pageSize'              => $perPage,
            'page'                  => 1,
            $this->apiKeyParamName  => $this->apiKey,
        ], $this->otherParams);

        foreach ($categories as $category) {
            $queryParams['category'] = $category;
            $response = Http::get($this->apiUrl, $queryParams);
            $jsonResponse = $response->json();
            if ($response->ok() && $jsonResponse['status'] == 'ok') {
                // Save news articles into the news table
                foreach ($jsonResponse['articles'] as $item) {
                    parent::saveNews(
                        $item['title'],
                        $item['content'],
                        $category,
                        !empty($item['author']) ? $item['author'] : null,
                        $item['urlToImage'] ?? null,
                        $item['url'],
                        $item['publishedAt'],
                        News::NEWS_API_SOURCE_ID
                    );
                }
            }
        }
        if ($response->ok()) {
            return parent::successResponse();
        }
        return parent::failedResponse($jsonResponse);
    }
}
