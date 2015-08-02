<?php

namespace Spectator\Processing;

use App;
use Monolog\Formatter\ChromePHPFormatter;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class Processor
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param string $logChannel
     */
    public function __construct($logChannel)
    {
        $this->logger = new Logger($logChannel);

        $logPath = storage_path('logs/' . $logChannel . '.log');

        if(!file_exists($logPath)) {
            touch($logPath);
        }

        $this->logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));

        if(App::environment('local')) {
            $devHandler = new ChromePHPHandler($logPath, Logger::DEBUG);
            $devHandler->setFormatter(new ChromePHPFormatter());

            $this->logger->pushHandler($devHandler);
        }
    }

    protected function log($message, $context = [])
    {
        $this->logger->addInfo($message, $context);
    }

    protected function warn($message, $context = [])
    {
        $this->logger->addWarning($message, $context);
    }

    protected function error($message, $context = [])
    {
        $this->logger->addAlert($message, $context);
    }
}