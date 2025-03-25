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

namespace App\Controller;

use App\Service\SubjectService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Hyperf\Swagger\Annotation as SA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[SA\HyperfServer(name: 'http')]
#[Controller]
class SubjectController
{
    #[Inject]
    protected SubjectService $subjectService;

    #[Inject]
    protected ResponseFactory $response;

    #[SA\Get(
        path: '/subjects',
        summary: 'Lista os assuntos',
        description: 'Retorna todos os assuntos ou filtra por descrição se o parâmetro "title" for fornecido',
    )]
    #[SA\Parameter(
        name: 'title',
        in: 'query',
        description: 'Descrição do assunto para filtrar os resultados',
        required: false,
        schema: new SA\Schema(type: 'string'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Lista os assuntos',
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
                            description: 'Lista de assuntos',
                            type: 'array',
                            items: new SA\Items(type: 'object'),
                        ),
                    ],
                ),
            ),
        ],
    )]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $title = $queryParams['title'] ?? null;

        $books = [];

        if ($title) {
            $books = $this->subjectService->getSubjectsByDescription($title);
        } else {
            $books = $this->subjectService->getAllSubjects();
        }

        return $this->response->json([
            'success' => true,
            'data' => $books,
        ]);
    }

    #[SA\Get(
        path: '/subjects/{id}',
        summary: 'Retorna um assunto pelo ID',
        description: 'Retorna as informações de um assunto com base no seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do assunto a ser retornada',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Subject found',
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
                            description: 'Informações da matéria',
                            type: 'object',
                        ),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 404,
        description: 'Subject not found',
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
    public function show(int $id)
    {
        $subject = $this->subjectService->getSubjectById($id);
        if (! $subject) {
            return $this->response->json(['success' => false, 'message' => 'Subject not found'], 404);
        }
        return $this->response->json(['success' => true, 'data' => $subject]);
    }

    #[SA\Post(
        path: '/subjects',
        summary: 'Cria um novo assunto',
        description: 'Adiciona um novo assunto ao sistema',
    )]
    #[SA\RequestBody(
        description: 'Dados do assunto a ser criada',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'description', description: 'Description', type: 'string'),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 201,
        description: 'Subject created successfully',
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
                            description: 'Informações da matéria criada',
                            type: 'object',
                        ),
                    ],
                ),
            ),
        ],
    )]
    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $book = $this->subjectService->createSubject($data);

        return $this->response->json(['success' => true, 'data' => $book], 201);
    }

    #[SA\Put(
        path: '/subjects/{id}',
        summary: 'Atualiza as informações de um assunto',
        description: 'Atualiza os dados de um assunto existente com base no seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID da assunto a ser atualizado',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\RequestBody(
        description: 'Dados atualizados do subject',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'description',
                            description: 'Description',
                            type: 'string',
                        ),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 200,
        description: 'Subject updated successfully',
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
                            description: 'Mensagem de confirmação',
                            type: 'string',
                        ),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 404,
        description: 'Subject not found',
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
    public function update(int $id, ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $updated = $this->subjectService->updateSubject($id, $data);

        if (! $updated) {
            return $this->response->json(['success' => false, 'message' => 'Subject not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Subject updated successfully']);
    }

    #[SA\Delete(
        path: '/subjects/{id}',
        summary: 'Deleta uma matéria pelo ID',
        description: 'Remove uma matéria do sistema pelo seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID da matéria a ser deletada',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Matéria deletada com sucesso',
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
                            description: 'Mensagem de confirmação',
                            type: 'string',
                        ),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 404,
        description: 'Matéria não encontrada',
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
    public function destroy(int $id)
    {
        $deleted = $this->subjectService->deleteSubject($id);

        if (! $deleted) {
            return $this->response->json(['success' => false, 'message' => 'Subject not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Subject deleted successfully']);
    }
}
