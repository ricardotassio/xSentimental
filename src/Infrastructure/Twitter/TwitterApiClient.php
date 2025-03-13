<?php

namespace App\Infrastructure\Twitter;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitterApiClient implements TwitterApiClientInterface
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $endpoint = 'https://api.twitter.com/2/tweets/search/recent';

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function searchTweetsByHashtag(string $hashtag): array
    {
        $hashtag = trim($hashtag);
        $searchQuery = str_starts_with($hashtag, '#') ? $hashtag : '#' . $hashtag;
        if ($searchQuery === '#' || $searchQuery === '') {
            return [];
        }

        $response = $this->httpClient->request('GET', $this->endpoint, [
            'query' => [
                'query'        => $searchQuery,
                'max_results'  => 10,
                'tweet.fields' => 'id,text,created_at',
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
        ]);

        $data = $response->toArray();
        if (!isset($data['data']) || !is_array($data['data'])) {
            return [];
        }

        $tweets = [];
        foreach ($data['data'] as $tweetData) {
            $tweets[] = [
                'id'   => $tweetData['id']   ?? '',
                'text' => $tweetData['text'] ?? '',
            ];
        }

        return $tweets;
    }
}
