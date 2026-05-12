<?php

namespace App\Command;

use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:video-tokens',
    description: 'Generate a token for every video row that has token IS NULL.',
)]
class VideoTokensCommand extends Command
{
    public function __construct(
        private readonly VideoRepository        $videoRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $videos = $this->videoRepository->findBy(['token' => null]);

        $io->title('Seed video tokens');
        $io->definitionList(['Found' => count($videos) . ' video(s) without token']);

        if ($videos === []) {
            $io->success('Nothing to do.');
            return Command::SUCCESS;
        }

        foreach ($videos as $video) {
            $video->resetToken();
            $io->text(sprintf(' • Video #%d  token set', $video->getId()));
        }

        $this->entityManager->flush();
        $io->success(sprintf('%d video(s) seeded.', count($videos)));

        return Command::SUCCESS;
    }
}
