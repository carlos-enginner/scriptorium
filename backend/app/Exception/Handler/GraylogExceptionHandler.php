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

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class GraylogExceptionHandler extends ExceptionHandler
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $statusCode = 500;
        $errorData = ['message' => 'Algo deu errado!'];

        $errors = [];
        if ($throwable instanceof ValidationException) {
            $statusCode = 422;
            $errors = $throwable->validator->errors();
        }

        $this->logger->error('Internal Server Error.', [
            'exception' => get_class($throwable),
            'message' => $throwable->getMessage(),
            'errors' => $errors,
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
            'status_code' => $statusCode,
        ]);

        return $response->withStatus($statusCode)->withBody(
            new SwooleStream(json_encode($errorData, JSON_UNESCAPED_UNICODE)),
        );
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
