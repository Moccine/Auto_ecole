<?php


namespace App\Service;

use Monolog\Handler\RotatingFileHandler;
use Psr\Log\LoggerInterface;

class PaymentLogger
{
    /** @var string */
    private $path;
    /** @var LoggerInterface */
    private $logger;

    /**
     * PaymentLogger constructor.
     * @param $path
     * @param $logger
     */
    public function __construct(string $path, LoggerInterface $logger)
    {
        $this->path = $path;
        $this->logger = $logger;
        $path = sprintf('%spayment.log', $this->path);
        $this->logger->pushHandler(new RotatingFileHandler($path));
    }

    /**
     * @param $message
     */
    public function logInfo($message)
    {
        $this->logger->notice($message);
    }
    /**
     * @param $message
     */
    public function logAlert($message)
    {
        $this->logger->alert($message);
    }
}
