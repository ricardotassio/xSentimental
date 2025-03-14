<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Tweet\Entity\Tweet;
use App\Domain\Tweet\Repository\TweetRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTweetRepository implements TweetRepositoryInterface {
    private EntityManagerInterface $em;
    private $repository;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->repository = $em->getRepository(Tweet::class);
    }

    public function save(Tweet $tweet): void 
    {
        $existingTweet = $this->em
            ->getRepository(Tweet::class)
            ->find($tweet->getId());

        if (!$existingTweet) {
          $this->em->persist($tweet);
          $this->em->flush();
        }
    }

    public function findById(string $id): ?Tweet {
        return $this->repository->find($id);
    }

    public function findByHashtag(string $hashtag): array {
        return $this->repository->createQueryBuilder('t')
            ->where('t.content LIKE :hashtag')
            ->setParameter('hashtag', '%' . $hashtag . '%')
            ->getQuery()
            ->getResult();
    }
    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
