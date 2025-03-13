<?php

namespace App\Domain\Tweet\Entity;

use App\Domain\Tweet\Value\Sentiment;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tweets')]
class Tweet
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $sentiment;

    public function __construct(TweetId $id, string $content, ?Sentiment $sentiment = null)
    {
        $this->id = $id->getId();
        $this->content = $content;
        $this->sentiment = $sentiment?->value;
    }

    public function getId(): TweetId
    {
        return new TweetId($this->id);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSentiment(): ?Sentiment
    {
        return $this->sentiment ? Sentiment::from($this->sentiment) : null;
    }

    public function setSentiment(Sentiment $sentiment): void
    {
        $this->sentiment = $sentiment->value;
    }
}
