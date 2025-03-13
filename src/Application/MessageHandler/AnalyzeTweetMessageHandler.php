<?php

namespace App\Application\MessageHandler;

use App\Application\Message\AnalyzeTweetMessage;
use App\Application\UseCase\AnalyzeTweetSentimentUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AnalyzeTweetMessageHandler
{
    private AnalyzeTweetSentimentUseCase $analyzeTweetSentimentUseCase;

    public function __construct(AnalyzeTweetSentimentUseCase $analyzeTweetSentimentUseCase)
    {
        $this->analyzeTweetSentimentUseCase = $analyzeTweetSentimentUseCase;
    }

    public function __invoke(AnalyzeTweetMessage $message): void
    {
        $this->analyzeTweetSentimentUseCase->execute($message->getTweetId());
    }
}
