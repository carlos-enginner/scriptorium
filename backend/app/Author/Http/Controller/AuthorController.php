<?php

namespace App\Author\Http\Controller;

use App\Author\UseCase\CreateAuthorUseCase;
use App\Author\UseCase\DeleteAuthorUseCase;
use App\Author\UseCase\GetAllAuthorUseCase;
use App\Author\UseCase\GetByIdAuthorUseCase;
use App\Author\UseCase\UpdateAuthorUseCase;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Hyperf\Swagger\Annotation as SA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[SA\Server('v2')]
#[SA\HyperfServer(name: 'http')]
#[Controller(prefix: '/v2')]
class AuthorController
{
    #[Inject]
    protected ResponseFactory $response;

    #[Inject]
    protected GetAllAuthorUseCase $getAllAuthorsUseCase;

    #[Inject]
    protected GetByIdAuthorUseCase $getByIdAuthorsUseCase;

    #[Inject]
    protected CreateAuthorUseCase $createAuthorsUseCase;

    #[Inject]
    protected UpdateAuthorUseCase $updateAuthorUseCase;

    #[Inject]
    protected DeleteAuthorUseCase $deleteAuthorUseCase;

    #[SA\Get(
        path: '/v2/authors',
        summary: 'Lista os autores',
        description: 'Retorna uma lista de autores. Permite filtrar pelo nome.',
    )]
    #[SA\Parameter(
        name: 'name',
        in: 'query',
        description: 'Filtra autores pelo nome',
        required: false,
        schema: new SA\Schema(type: 'string'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Lista de autores',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(
                            property: 'data',
                            description: 'Lista de autores',
                            type: 'array',
                        ),
                    ],
                ),
            ),
        ],
    )]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $name = $queryParams['name'] ?? null;

        $authors = $this->getAllAuthorsUseCase->execute();

        return $this->response->json([
            'success' => true,
            'data' => $authors,
        ]);
    }

    #[SA\Get(
        path: '/v2/authors/{id}',
        summary: 'Obtém detalhes de um autor',
        description: 'Retorna os detalhes de um autor específico pelo seu ID.',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do autor a ser buscado',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Detalhes do autor',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(
                            property: 'data',
                            description: 'Dados do autor',
                            type: 'object',
                        ),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 404,
        description: 'Autor não encontrado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(
                            property: 'message',
                            description: 'Mensagem de erro',
                            type: 'string',
                        ),
                    ],
                ),
            ),
        ],
    )]
    public function getById(int $id)
    {
        $author = $this->getByIdAuthorsUseCase->execute($id);
        if (! $author) {
            return $this->response->json(['success' => false, 'message' => 'Author not found'], 404);
        }
        return $this->response->json(['success' => true, 'data' => $author]);
    }

    #[SA\Post(
        path: '/v2/authors',
        summary: 'Cria um novo autor',
        description: 'Adiciona um novo autor ao sistema.',
    )]
    #[SA\RequestBody(
        description: 'Dados para criar um novo autor',
        required: true,
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    required: ['name'],
                    properties: [
                        new SA\Property(
                            property: 'name',
                            description: 'Nome do autor',
                            type: 'string',
                        ),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 201,
        description: 'Autor criado com sucesso',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(
                            property: 'data',
                            description: 'Dados do autor criado',
                            type: 'object',
                        ),
                    ],
                ),
            ),
        ],
    )]
    public function create(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $author = $this->createAuthorsUseCase->execute($data);

        return $this->response->json(['success' => true, 'data' => $author], 201);
    }

    #[SA\Put(
        path: '/v2/authors/{id}',
        summary: 'Atualiza um autor',
        description: 'Modifica os dados de um autor existente pelo ID.',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do autor a ser atualizado',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\RequestBody(
        description: 'Dados para atualizar o autor',
        required: true,
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'name', description: 'Nome do autor', type: 'string'),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 200,
        description: 'Autor atualizado com sucesso',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(property: 'message', description: 'Mensagem de confirmação', type: 'string'),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 404,
        description: 'Autor não encontrado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(property: 'message', description: 'Mensagem de erro', type: 'string'),
                    ],
                ),
            ),
        ],
    )]
    public function update(int $id, ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $updated = $this->updateAuthorUseCase->execute($id, $data);

        if (!$updated) {
            return $this->response->json(['success' => false, 'message' => 'Author not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Author updated successfully']);
    }

    #[SA\Delete(
        path: '/v2/authors/{id}',
        summary: 'Deleta um autor pelo ID',
        description: 'Remove um autor do sistema pelo seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do autor a ser deletado',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Autor deletado com sucesso',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(property: 'message', description: 'Mensagem de confirmação', type: 'string'),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 404,
        description: 'Autor não encontrado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'success',
                            description: 'Indica se a operação foi bem-sucedida',
                            type: 'boolean',
                        ),
                        new SA\Property(property: 'message', description: 'Mensagem de erro', type: 'string'),
                    ],
                ),
            ),
        ],
    )]
    public function destroy(int $id)
    {
        $deleted = $this->deleteAuthorUseCase->execute($id);

        if (! $deleted) {
            return $this->response->json(['success' => false, 'message' => 'Author not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Author deleted successfully']);
    }
}
