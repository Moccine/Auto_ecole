<?php


namespace App\Service\import;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ImportService
{
    protected $container;
    protected $translator;
    protected $logger;
    protected $csvFolderPath;

    public function __construct(ContainerInterface $container, LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->container = $container;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->csvFolderPath = $this->container->getParameter('csv_folder_path');
    }

    /**
     * @param $csvFolderPath
     */
    public function setCsvFolderPath($csvFolderPath)
    {
        $this->csvFolderPath = $csvFolderPath;
    }

    /**
     * @param OutputInterface $output
     * @param bool            $skipUpdate
     */
    public function importData(OutputInterface $output, $skipUpdate = false)
    {
        // Order is important
        $services = [
            'FranceLocation',
        ];

        foreach ($services as $key => $name) {
            $service = $this->container->get("App\Service\Import\\{$name}ImportService");
            $stats = $service->import($output, $name, $this->csvFolderPath, $this->logger, $skipUpdate);

            $importDate = new \DateTime();
            $recap = sprintf(
                '%s: Import %s [Insert: %s, Update: %s, Error: %s, Total: %s] ',
                $importDate->format('d/m/Y  H:i:s'),
                $name,
                $stats['insert'],
                $stats['update'],
                $stats['error'],
                array_sum($stats)
            );
            $this->logger->info($recap);
            $output->writeln($recap);
            unset($service);
        }
    }
}
