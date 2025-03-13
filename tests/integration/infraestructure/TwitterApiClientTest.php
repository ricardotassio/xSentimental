<?php

namespace Tests\Infrastructure\Twitter;

use App\Infrastructure\Twitter\TwitterApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitterApiClientTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private TwitterApiClient $client;

    protected function setUp(): void
    {
        $this->httpClient = HttpClient::create();
        $this->client = new TwitterApiClient($this->httpClient, $_ENV['TWITTER_API_KEY'] ?? 'fake_key');
    }

    public function testSearchTweetsByHashtagIntegration(): void
    {
        $result = $this->client->searchTweetsByHashtag('#Test');   
        $this->assertIsArray($result);
    }
}
