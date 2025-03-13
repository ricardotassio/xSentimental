<?php

namespace App\UI\Http\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Application\UseCase\SearchTweetsUseCase;
use App\Domain\Tweet\Repository\TweetRepositoryInterface;

class TweetController extends AbstractController 
{
    private SearchTweetsUseCase $searchTweetsUseCase;

    public function __construct(SearchTweetsUseCase $searchTweetsUseCase)
    {
        $this->searchTweetsUseCase = $searchTweetsUseCase;
    }

    #[Route('/', name: 'tweet_search', methods: ['GET', 'POST'])]
    public function index(Request $request): Response 
    {
        $tweets = [];

        if ($request->isMethod('POST')) {
            $hashtag = $request->request->get('hashtag');
            // O UseCase pode retornar array de tweets ou string de erro/aviso
            $tweets = $this->searchTweetsUseCase->execute($hashtag);
        }

        return $this->render('tweets/index.html.twig', [
            'tweets' => $tweets,
        ]);
    }

    #[Route('/tweets/sentiments', name: 'tweets_sentiments', methods: ['GET'])]
    public function sentiments(TweetRepositoryInterface $tweetRepository): JsonResponse
    {
        // Exemplo: se você tiver tweets salvos no banco, busque aqui
        $tweets = $tweetRepository->findAll(); // ou findByHashtag(...)
        
        $data = [];
        foreach ($tweets as $tweet) {
            $data[] = [
                'id'        => $tweet->getId()->getId(),
                'sentiment' => $tweet->getSentiment()
                    ? $tweet->getSentiment()->value
                    : 'Aguardando análise...',
            ];
        }

        return new JsonResponse($data);
    }
}
