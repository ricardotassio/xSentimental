<?php

namespace App\Application\UseCase;

use App\Domain\Tweet\Repository\TweetRepositoryInterface;
use App\Infrastructure\LLM\SentimentAnalysisClientInterface;
use App\Domain\Tweet\Service\TweetSentimentService;

class AnalyzeTweetSentimentUseCase 
{
    private TweetRepositoryInterface $tweetRepository;
    private SentimentAnalysisClientInterface $llmClient;
    private TweetSentimentService $tweetSentimentService;

    public function __construct(
        TweetRepositoryInterface $tweetRepository,
        SentimentAnalysisClientInterface $llmClient,
        TweetSentimentService $tweetSentimentService
    ) {
        $this->tweetRepository = $tweetRepository;
        $this->llmClient = $llmClient;
        $this->tweetSentimentService = $tweetSentimentService;
    }

    public function execute(string $tweetId): void 
    {
        $tweet = $this->tweetRepository->findById($tweetId);
        if (!$tweet) {
            return;
        }

        $result = $this->llmClient->analyzeSentiment($tweet->getContent());
        $this->tweetSentimentService->updateSentiment($tweet, $result);
        $this->tweetRepository->save($tweet);
    }
}
