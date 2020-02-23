<?php

namespace App\Command;

use App\Service\import\ImportService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportFranceLocationCommand extends Command
{
    protected static $defaultName = 'app:import:france:location';

    protected $em;
    protected $logger;
    protected $importService;

    /**
     * OiseImporDbCommand constructor.
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param ImportService $importManager
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, ImportService $importService)
    {
        parent::__construct();
        $this->em = $em;
        $this->logger = $logger;
        $this->importService = $importService;
    }
    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->importService->importData($output);
        $io->success('Base importé avec succès');

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
