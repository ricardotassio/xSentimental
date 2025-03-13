<?php
namespace App\Application\UseCase;

use App\Domain\Tweet\Entity\Tweet;
use App\Domain\Tweet\Entity\TweetId;
use App\Domain\Tweet\Repository\TweetRepositoryInterface;
use App\Infrastructure\Twitter\TwitterApiClientInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Message\AnalyzeTweetMessage;

class SearchTweetsUseCase 
{
    private TwitterApiClientInterface $twitterClient;
    private TweetRepositoryInterface $tweetRepository;
    private MessageBusInterface $messageBus;

    public function __construct(
        TwitterApiClientInterface $twitterClient,
        TweetRepositoryInterface $tweetRepository,
        MessageBusInterface $messageBus
    ) {
        $this->twitterClient = $twitterClient;
        $this->tweetRepository = $tweetRepository;
        $this->messageBus = $messageBus;
    }

    public function execute(string $hashtag): array 
    {
        $tweetsData = $this->twitterClient->searchTweetsByHashtag($hashtag);
        if (isset($tweetsData['message'])) {
            return $tweetsData;
        }

        $tweets = [];
        foreach ($tweetsData as $data) {
            $tweet = new Tweet(new TweetId($data['id']), $data['text']);
            $this->tweetRepository->save($tweet);
            $this->messageBus->dispatch(new AnalyzeTweetMessage($tweet->getId()->getId()));
            $tweets[] = $tweet;
        }
        return $tweets;
    }
}
