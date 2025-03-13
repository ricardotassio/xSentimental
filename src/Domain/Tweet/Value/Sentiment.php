<?php 

namespace App\Domain\Tweet\Value;

enum Sentiment: string {
    case POSITIVE = 'positive';
    case NEGATIVE = 'negative';
    case NEUTRAL = 'neutral';
}