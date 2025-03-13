<?php

namespace App\UI\Console\Command;

use App\Infrastructure\LLM\SentimentAnalysisClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestSentimentCommand extends Command
{
    protected static $defaultName = 'app:test-sentiment';
    private SentimentAnalysisClientInterface $sentimentClient;

    public function __construct(SentimentAnalysisClientInterface $sentimentClient)
    {
        parent::__construct();
        $this->sentimentClient = $sentimentClient;
    }

    protected function configure()
    {
        $this->setName('app:test-sentiment');
        $this
            ->setDescription('Testa a análise de sentimento via API do Akash.')
            ->addArgument('text', InputArgument::REQUIRED, 'Texto para análise');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $text = $input->getArgument('text');
        $result = $this->sentimentClient->analyzeSentiment($text);
        $output->writeln("Sentimento: " . $result);
        return Command::SUCCESS;
    }
}
