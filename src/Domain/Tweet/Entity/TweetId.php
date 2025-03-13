<?php 

namespace App\Domain\Tweet\Entity;


class TweetId 
{
  private string $id;
  /**
   *
   * @param string|null
   */
  public function __construct(string $id)
  {
    $this->id = $id ?? uniqid();
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function __toString(): string
  {
      return $this->id;
  }
}