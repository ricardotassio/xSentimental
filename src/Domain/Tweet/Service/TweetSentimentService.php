<?php

namespace App\Domain\Tweet\Service;

use App\Domain\Tweet\Entity\Tweet;
use App\Domain\Tweet\Value\Sentiment;

class TweetSentimentService {
    public function updateSentiment(Tweet $tweet, string $analysisResult): void {
        $sentiment = match (strtolower($analysisResult)) {
            'positivo' => Sentiment::POSITIVE,
            'negativo' => Sentiment::NEGATIVE,
            default => Sentiment::NEUTRAL,
        };
        $tweet->setSentiment($sentiment);
    }
}
