<?php

namespace Tests\Domain\Tweet;

use App\Domain\Tweet\Entity\TweetId;
use PHPUnit\Framework\TestCase;

class TweetIdTest extends TestCase
{
    public function testCanCreateTweetId(): void
    {
        $id = new TweetId('abc123');
        $this->assertEquals('abc123', $id->getId());
    }

    public function testGeneratesUniqueIdWhenNullIsPassed(): void
    {
        $id = new TweetId(uniqid());
        $this->assertNotEmpty($id->getId());
    }

    public function testToStringReturnsId(): void
    {
        $id = new TweetId('xyz456');
        $this->assertEquals('xyz456', (string)$id);
    }
}
