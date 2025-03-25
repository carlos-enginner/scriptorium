<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    protected LoggerInterface $graylogLogger;

    public function __construct(
        protected StdoutLoggerInterface $logger,
        LoggerFactory $loggerFactory
    ) {
        $this->graylogLogger = $loggerFactory->get();
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $statusCode = 500;
        $this->graylogLogger->error('Internal Server Error.', [
            'exception' => get_class($throwable),
            'message' => $throwable->getMessage(),
            'errors' => [],
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
            'status_code' => $statusCode,
        ]);

        $this->logger->error(
            sprintf(
                '%s[%s] in %s',
                $throwable->getMessage(),
                $throwable->getLine(),
                $throwable->getFile(),
            ),
        );
        $this->logger->error($throwable->getTraceAsString());
        return $response->withHeader('Server', 'Hyperf')
            ->withStatus(500)
            ->withBody(
                new SwooleStream('Internal Server Error.'),
            );
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
