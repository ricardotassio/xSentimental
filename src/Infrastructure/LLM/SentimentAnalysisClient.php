<?php

namespace App\Infrastructure\LLM;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class SentimentAnalysisClient implements SentimentAnalysisClientInterface
{
    private const SENTIMENT_NEUTRAL = 'neutro';

    private HttpClientInterface $httpClient;
    private string $openAiApiKey;
    private string $endpoint;

    public function __construct(HttpClientInterface $httpClient, string $openAiApiKey, string $endpoint)
    {
        $this->httpClient   = $httpClient;
        $this->openAiApiKey = $openAiApiKey;
        $this->endpoint     = $endpoint;
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
                    'model'       => 'gpt-3.5-turbo',
                    'messages'    => [
                        [
                            'role'    => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'max_tokens'  => 10,
                    'temperature' => 0.0,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->openAiApiKey,
                    'Content-Type'  => 'application/json',
                ],
            ]);

            $data = $response->toArray();
        } catch (TransportExceptionInterface | ClientExceptionInterface | ServerExceptionInterface $e) {
            return self::SENTIMENT_NEUTRAL;
        }

        if (!empty($data['choices'][0]['message']['content'])) {
            return strtolower(trim($data['choices'][0]['message']['content']));
        }

        return self::SENTIMENT_NEUTRAL;
    }
}
