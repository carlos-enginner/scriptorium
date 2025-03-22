<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthorService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Swagger\Annotation as SA;

#[SA\HyperfServer(name: 'http')]
#[Controller]
class AuthorController
{
    #[Inject]
    protected AuthorService $authorService;

    #[Inject]
    protected ResponseFactory $response;

    #[SA\Get(path: '/author', summary: 'Lista todos os autores ou busca por nome', tags: ['Autores'])]
    #[SA\Parameter(
        name: 'name',
        in: 'query',
        description: 'Filtra autores pelo nome',
        required: false,
        schema: new SA\Schema(type: 'string')
    )]
    #[SA\Response(
        response: 200,
        description: 'Lista de autores',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(
                            property: 'data',
                            description: 'Lista de autores',
                            type: 'array',
                            items: new SA\Items(ref: '#/components/schemas/Author')
                        ),
                    ]
                )
            ),
        ]
    )]
    #[GetMapping(path: "author")]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $name = $queryParams['name'] ?? null;

        $authors = [];

        if ($name) {
            $authors = $this->authorService->getAuthorByName($name);
        } else {
            $authors = $this->authorService->getAllAuthors();
        }

        return $this->response->json([
            'success' => true,
            'data' => $authors
        ]);
    }

    #[GetMapping(path: "author/{id}")]
    public function show(int $id)
    {
        $author = $this->authorService->getAuthorById($id);
        if (!$author) {
            return $this->response->json(['success' => false, 'message' => 'Author not found'], 404);
        }
        return $this->response->json(['success' => true, 'data' => $author]);
    }

    #[PostMapping(path: "author")]
    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $author = $this->authorService->createAuthor($data);

        return $this->response->json(['success' => true, 'data' => $author], 201);
    }

    #[PutMapping(path: "author/{id}")]
    public function update(int $id, ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $updated = $this->authorService->updateAuthor($id, $data);

        if (!$updated) {
            return $this->response->json(['success' => false, 'message' => 'Author not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Author updated successfully']);
    }

    #[DeleteMapping(path: "author/{id}")]
    public function destroy(int $id)
    {
        $deleted = $this->authorService->deleteAuthor($id);

        if (!$deleted) {
            return $this->response->json(['success' => false, 'message' => 'Author not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Author deleted successfully']);
    }
}
