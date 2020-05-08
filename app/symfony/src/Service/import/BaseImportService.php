<?php

namespace App\Service\import;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class BaseImportService
{
    protected $batchSize = 1;
    protected $filename;
    protected $em;
    protected $delimiter;
    protected $mapping;
    protected $logger;

    public function __construct($filename, EntityManagerInterface $em, LoggerInterface $logger, $delimiter = ',')
    {
        $this->filename = $filename;
        $this->em = $em;
        $this->delimiter = $delimiter;
        $this->logger = $logger;
    }

    /**
     * @param OutputInterface $output
     * @param $name
     * @param $folderPath
     * @param LoggerInterface $logger
     * @param $skipUpdate
     *
     * @return array|string
     */
    public function import(OutputInterface $output, $name, $folderPath, LoggerInterface $logger, $skipUpdate)
    {
        $this->logger = $logger;

        $nbToFlush = 0;
        $stats = [
            'insert' => 0,
            'update' => 0,
            'error' => 0,
            'message' => '',
        ];

        $hd = fopen($folderPath . $this->filename, 'r');

        if (!$hd) {
            throw new FileNotFoundException($folderPath . $this->filename . ': file not found');
        }

        $nbLines = -1; // do not count header
        while (!feof($hd)) {
            $line = fgetcsv($hd);
            ++$nbLines;
        }

        $progress = $this->renderProgress($output, $nbLines, $name);

        rewind($hd);
        $header = fgetcsv($hd, 2048, $this->delimiter);
        $data = fgetcsv($hd, 2048, $this->delimiter, '"', ',');

        while (false !== $data) {
            try {
                $importType = $this->importData($folderPath, $header, $data, $skipUpdate, $logger);
                ++$stats[$importType];
            } catch (\Exception $e) {
                $logger->error($e->getMessage() . $e->getTraceAsString());
                $output->writeln('Error: ' . $e->getMessage());
                $stats['message'] .= $e->getMessage() . "\n";
                ++$stats['error'];
            }
            ++$nbToFlush;
            if (0 === ($nbToFlush % $this->batchSize)) {
                $this->em->flush();
                $this->em->clear();
            }
            $data = fgetcsv($hd, 2048, $this->delimiter);
            $progress->advance();
        }
        try {
            $this->em->flush();
            $this->em->clear();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $progress->finish();
        $output->writeln('');

        fclose($hd);

        return $stats;
    }

    /**
     * @param OutputInterface $output
     * @param $nbItems
     * @param $name
     *
     * @return ProgressBar
     */
    public function renderProgress(OutputInterface $output, $nbItems, $name)
    {
        $progress = new ProgressBar($output, $nbItems);

        if (0 === $nbItems) {
            $progress->setFormat('%message% %current%/%max% [%bar%] %percent:3s%% RAM: %memory:6s%');
        } else {
            $progress->setFormat('%message% %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% RAM: %memory:6s%');
        }

        $progress->setMessage('- ' . $name);
        $progress->setBarWidth(70);
        $progress->start();

        return $progress;
    }

    /**
     * @param $folderPath
     * @param $header
     * @param array $data
     * @param $skipUpdate
     *
     * @throws \Exception
     */
    public function importData($folderPath, $header, array $data, $skipUpdate)
    {
        throw new \Exception('method "importData" must be implemented for class ' . get_class($this));
    }

    /**
     * @param $entity
     * @param $data
     *
     * @throws \Exception
     */
    protected function mapSimpleFields($entity, $data)
    {
        foreach ($this->mapping as $key => $setter) {
            if (null !== $setter) {
                if (is_array($setter) && '' !== $setter[1]) {
                    switch ($setter[0]) {
                        case 'bool':
                            $setter = $setter[1];
                            $entity->$setter(null !== $data[$key] && '0' != $data[$key]);
                            break;
                        case 'int':
                            $setter = $setter[1];
                            $entity->$setter((int)$data[$key]);
                            break;
                        case 'float':
                            $setter = $setter[1];
                            $entity->$setter(floatval(str_replace(',', '.', $this->removeBlanc($data[$key]))));
                            break;
                        case 'datetime':
                            if (2 === count($setter)) { // default format
                                $setter = $setter[1];
                                if ('' == $data[$key]) {
                                    $entity->$setter(null);
                                } else {
                                    $entity->$setter(\DateTime::createFromFormat('Y-m-d H:i:s.u', $data[$key]));
                                }
                            } else { // custom format
                                $setter = $setter[2];
                                if ('' == $data[$key]) {
                                    $entity->$setter(null);
                                } else {
                                    $entity->$setter(\DateTime::createFromFormat($setter[1], $data[$key]));
                                }
                            }
                            break;
                        default:
                            throw new \Exception('type ' . $setter[0] . ' unknown');
                            break;
                    }
                } else {
                    $entity->$setter($data[$key]);
                }
            }
        }
    }

    /**
     * @param $tring
     * @return string|string[]|null
     */
    public function removeBlanc($tring)
    {
        return preg_replace(
            "/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/",
            "",
            $tring
        );
    }
}
