<?php

namespace App\Actor\Http\Controller;

use App\Actor\UseCase\GetAllActorsUseCase;
use App\Service\AuthorService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Hyperf\Swagger\Annotation as SA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[SA\HyperfServer(name: 'http')]
#[Controller(prefix: '/v2/actors')]
class ActorController
{
    #[Inject]
    protected ResponseFactory $response;

    #[Inject]
    protected GetAllActorsUseCase $getAllActorsUseCase;

    #[SA\Get(
        path: '/v2/actors',
        summary: 'Lista os autores',
        description: 'Retorna uma lista de autores. Permite filtrar pelo nome.',
    )]

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $name = $queryParams['name'] ?? null;

        $useCase = $this->getAllActorsUseCase->execute();

        return $this->response->json([
            'success' => true,
            'data' => $useCase,
        ]);
    }
}
