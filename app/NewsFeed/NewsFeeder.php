<?php

namespace App\NewsFeed;

use App\Models\News;
use App\Models\Source;

class NewsFeeder {
    protected $apiUrl;
    protected $apiKey;
    protected $apiKeyParamName;
    protected $otherParams;
    protected $source;
    protected $page;

    /**
     * Set the properties of the NewsFeeder instance based on configuration settings.
     *
     * @param NewsFeeder $instance The NewsFeeder instance to set properties on.
     * @param array $config The configuration settings for the NewsFeeder.
     * @param int $sourceId The ID of the news source.
     */
    public static function set(&$instance, $config, $sourceId)
    {
        $instance->apiUrl = $config['api_url'];
        $instance->apiKey = $config['api_key'];
        $instance->apiKeyParamName = $config['api_parameter_name'];
    }

    /**
     * Save a news article to the database.
     *
     * @param string $title The title of the news article.
     * @param string $body The body of the news article.
     * @param string $category The category of the news article.
     * @param string|null $author The author of the news article.
     * @param string|null $thumb The URL of the thumbnail image for the news article.
     * @param string $url The URL of the news article.
     * @param string $publishedAt The date and time the news article was published.
     * @param int $sourceId The ID of the news source.
     */
    public static function saveNews($title, $body, $category, $author, $thumb, $url, $publishedAt, $sourceId)
    {
        $news = News::firstOrCreate([
            'title' => $title,
            'author' => $author,
            'source_id' => $sourceId,
        ], [
            'body' => $body,
            'category' => $category,
            'thumb' => $thumb ?? 'https://placehold.co/400x200?text=No+Image',
            'web_url' => $url,
            'published_at' => date('Y-m-d H:i:s', strtotime($publishedAt)),
        ]);

        // If the record already existed, update the fields that may have changed
        if (!$news->wasRecentlyCreated) {
            $news->body = $body;
            $news->category = $category;
            $news->thumb = $thumb ?? 'https://placehold.co/400x200?text=No+Image';
            $news->web_url = $url;
            $news->published_at = date('Y-m-d H:i:s', strtotime($publishedAt));
            $news->save();
        }
    }

    /**
     * Return a success response.
     *
     * @return array The success response.
     */
    public static function successResponse()
    {
        return [
            'status'        => true,
            'msg'           => 'Fetched successfully and saved',
        ];
    }

    /**
     * Return a failed response.
     *
     * @param array $jsonResponse The JSON response from the API.
     * @return array The failed response.
     */
    public static function failedResponse($jsonResponse)
    {
        return [
            'status'    => false,
            'msg'       => isset($jsonResponse['message']) ? $jsonResponse['message'] : 'Internal server error',
        ];
    }
}
