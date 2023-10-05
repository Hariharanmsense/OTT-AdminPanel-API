<?php

namespace App\Factory;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Factory.
 */
final class LoggerFactory
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $level;

    /**
     * The constructor.
     *
     * @param array $settings The settings
     */
    public function __construct($path, $level)
    {
        $this->path = $path;
        $this->level = $level;
        $folderdate = date("d-m-y");
        $this->path = $this->path."/".$folderdate;
    }
    
    /*
     public function __construct(array $settings)
    {
        $this->path = (string)$settings['path'];
        $this->level = (int)$settings['level'];
    }
    */
    /**
     * @var array Handler
     */
    private $handler = [];

    /**
     * Build the logger.
     *
     * @param string $name The name
     *
     * @return LoggerInterface The logger
     */
    public function createInstance(string $name): LoggerInterface
    {
        $logger = new Logger($name);

        foreach ($this->handler as $handler) {
            $logger->pushHandler($handler);
        }

        $this->handler = [];

        return $logger;
    }

    public function getFileObject($fileHandler, $createInstance){       
       return $this->addFileHandler($fileHandler.'.log')->createInstance($createInstance);
    }

    /**
     * Add rotating file logger handler.
     *
     * @param string $filename The filename
     * @param int $level The level (optional)
     *
     * @return LoggerFactory The logger factory
     */
    public function addFileHandler(string $filename, int $level = null): self
    {
        
       
        $filename = sprintf('%s/%s', $this->path, $filename);

        $rotatingFileHandler = new RotatingFileHandler(
            $filename, 
            0,
            $level ?? $this->level,
            true,
            0644
        );
        $rotatingFileHandler->setFilenameFormat('{date}_{filename}', RotatingFileHandler::FILE_PER_DAY); // date format - format must be one of RotatingFileHandler::FILE_PER_DAY / ("Y-m-d"), RotatingFileHandler::FILE_PER_MONTH / ("Y-m") or RotatingFileHandler::FILE_PER_YEAR / ("Y")
        // The last "true" here tells monolog to remove empty []'s
		
		$loggerFormat = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $loggerTimeFormat = "Y-m-d H:i:s";
		 
        $rotatingFileHandler->setFormatter(
		
		
            new LineFormatter($loggerFormat, $loggerTimeFormat, true, true)
        );

        $this->handler[] = $rotatingFileHandler;

        return $this;
    }

    /**
     * Add a console logger.
     *
     * @param int $level The level (optional)
     *
     * @return self The instance
     */
    public function addConsoleHandler(int $level = null): self
    {
        $streamHandler = new StreamHandler('php://stdout', $level ?? $this->level);
        $streamHandler->setFormatter(new LineFormatter(null, null, false, true));

        $this->handler[] = $streamHandler;

        return $this;
    }
}
?>