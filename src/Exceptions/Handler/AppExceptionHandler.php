<?php

declare(strict_types=1);

namespace Meibuyu\Micro\Exceptions\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $stdoutLogger;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $stdoutLogger, LoggerFactory $loggerFactory)
    {
        $this->stdoutLogger = $stdoutLogger;
        $this->logger = $loggerFactory->get('Uncaught Exception');
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 捕获所有未捕获的异常
        $this->stopPropagation();
        $msg = sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile());
        $this->stdoutLogger->error($msg);
        $this->logger->error($msg);
        $this->stdoutLogger->error($throwable->getTraceAsString());
        return $response->withStatus(500)->withBody(new SwooleStream($msg));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
