<?php

declare(strict_types=1);

namespace App\Exception;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class GlobalExceptionHandler extends ExceptionHandler
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof ValidationException) {
            $this->stopPropagation();

            $errors = $throwable->validator->errors();

            $this->logger->error('Validation fields error', [
                'errors' => $errors,
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ]);

            return $response->withStatus(422)->withBody(
                new \Hyperf\HttpMessage\Stream\SwooleStream(json_encode([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $errors,
                ], JSON_UNESCAPED_UNICODE))
            );
        } else {
            $this->logger->error('Exception Captured', [
                'exception' => get_class($throwable),
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ]);
        }

        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
