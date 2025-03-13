<?php 

namespace App\Domain\Tweet\Repository;

use App\Domain\Tweet\Entity\Tweet;

interface TweetRepositoryInterface
{
  public function save(Tweet $tweet): void;
  public function findById(string $id): ?Tweet;
  public function findByHashtag(string $hashtag): array;
  public function findAll(): array;
}

