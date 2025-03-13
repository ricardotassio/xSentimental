<?php

namespace App\Infrastructure\LLM;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class SentimentAnalysisClient implements SentimentAnalysisClientInterface
{
    private HttpClientInterface $httpClient;
    private string $akashApiKey;
    private string $endpoint;

    public function __construct(HttpClientInterface $httpClient, string $akashApiKey, string $endpoint)
    {
        $this->httpClient = $httpClient;
        $this->akashApiKey = $akashApiKey;
        $this->endpoint = $endpoint;
    }

    public function analyzeSentiment(string $text): string
    {
        $prompt = sprintf(
            'Analise o sentimento deste tweet: "%s". Responda apenas com "positivo", "negativo" ou "neutro".',
            $text
        );

        try {
            $response = $this->httpClient->request('POST', $this->endpoint, [
                'json' => [
                    'messages' => [
                        [
                            'role'    => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'model'      => 'gpt-3.5-turbo',
                    'max_tokens' => 10,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->akashApiKey,
                    'Content-Type'  => 'application/json',
                ],
            ]);

            $data = $response->toArray();
        } catch (TransportExceptionInterface | ClientExceptionInterface | ServerExceptionInterface $e) {
            return 'neutro';
        }
        if (isset($data['choices']) && is_array($data['choices']) && count($data['choices']) > 0) {
            $message = $data['choices'][0]['message']['content'] ?? '';
            return strtolower(trim($message));
        }

        return 'neutro';
    }
}
