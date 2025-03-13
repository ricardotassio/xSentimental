<?php

namespace Tests\Infrastructure\Twitter;

use App\Infrastructure\Twitter\TwitterApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class TwitterApiClientTest extends TestCase
{
    public function testSearchTweetsByHashtagIntegration(): void
    {
        $twitterApiKey = getenv('TWITTER_API_KEY');
        // Se a chave não estiver definida ou for igual a "fake_key", pulamos o teste.
        if (!$twitterApiKey || $twitterApiKey === 'fake_key') {
            $this->markTestSkipped('TWITTER_API_KEY not set or invalid. Skipping integration test.');
        }

        $httpClient = HttpClient::create();
        $client = new TwitterApiClient($httpClient, $twitterApiKey);

        $result = $client->searchTweetsByHashtag('#Test');
        $this->assertIsArray($result);
    }
}
