<?php

namespace App\Infrastructure\Twitter;

interface TwitterApiClientInterface
{
    public function searchTweetsByHashtag(string $hashtag): array;
}
