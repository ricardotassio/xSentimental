<?php

namespace App\Application\Message;

class AnalyzeTweetMessage 
{
    private string $tweetId;

    public function __construct(string $tweetId) {
        $this->tweetId = $tweetId;
    }

    public function getTweetId(): string {
        return $this->tweetId;
    }
}
