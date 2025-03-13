<?php
namespace App\Infrastructure\LLM;

interface SentimentAnalysisClientInterface 
{
    public function analyzeSentiment(string $text): string;
}
