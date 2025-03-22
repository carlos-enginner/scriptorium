<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\Di\Annotation\Inject;
use App\Service\SubjectService;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Swagger\Annotation as SA;

#[SA\HyperfServer(name: 'http')]
#[Controller]
class SubjectController
{
    #[Inject]
    protected SubjectService $subjectService;

    #[Inject]
    protected ResponseFactory $response;

    #[SA\Get(
        path: '/assuntos',
        summary: 'Lista os assuntos',
        description: 'Retorna todos os assuntos ou filtra por descrição se o parâmetro "title" for fornecido',
    )]
    #[SA\Parameter(
        name: 'title',
        in: 'query',
        description: 'Descrição do assunto para filtrar os resultados',
        required: false,
        schema: new SA\Schema(type: 'string')
    )]
    #[SA\Response(
        response: 200,
        description: 'Lista de assuntos',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'data', description: 'Lista de assuntos', type: 'array', items: new SA\Items(type: 'object'))
                    ]
                )
            ),
        ]
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
            'data' => $books
        ]);
    }

    #[SA\Get(
        path: '/assuntos/{id}',
        summary: 'Retorna um assunto pelo ID',
        description: 'Retorna as informações de um assunto com base no seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do assunto a ser retornado',
        required: true,
        schema: new SA\Schema(type: 'integer')
    )]
    #[SA\Response(
        response: 200,
        description: 'Assunto encontrado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'data', description: 'Informações do assunto', type: 'object')
                    ]
                )
            ),
        ]
    )]
    #[SA\Response(
        response: 404,
        description: 'Assunto não encontrado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'message', description: 'Mensagem de erro', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    public function show(int $id)
    {
        $subject = $this->subjectService->getSubjectById($id);
        if (!$subject) {
            return $this->response->json(['success' => false, 'message' => 'Assunto não encontrado'], 404);
        }
        return $this->response->json(['success' => true, 'data' => $subject]);
    }

    #[SA\Post(
        path: '/assuntos',
        summary: 'Cria um novo assunto',
        description: 'Adiciona um novo assunto ao sistema',
    )]
    #[SA\RequestBody(
        description: 'Dados do assunto a ser criado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'description', description: 'Descrição do assunto', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    #[SA\Response(
        response: 201,
        description: 'Assunto criado com sucesso',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'data', description: 'Informações do assunto criado', type: 'object')
                    ]
                )
            ),
        ]
    )]
    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $subject = $this->subjectService->createSubject($data);

        return $this->response->json(['success' => true, 'data' => $subject], 201);
    }

    #[SA\Put(
        path: '/assuntos/{id}',
        summary: 'Atualiza as informações de um assunto',
        description: 'Atualiza os dados de um assunto existente com base no seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do assunto a ser atualizado',
        required: true,
        schema: new SA\Schema(type: 'integer')
    )]
    #[SA\RequestBody(
        description: 'Dados atualizados do assunto',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'description', description: 'Descrição do assunto', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    #[SA\Response(
        response: 200,
        description: 'Assunto atualizado com sucesso',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'message', description: 'Mensagem de confirmação', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    #[SA\Response(
        response: 404,
        description: 'Assunto não encontrado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'message', description: 'Mensagem de erro', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    public function update(int $id, ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $updated = $this->subjectService->updateSubject($id, $data);

        if (!$updated) {
            return $this->response->json(['success' => false, 'message' => 'Assunto não encontrado'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Assunto atualizado com sucesso']);
    }

    #[SA\Delete(
        path: '/assuntos/{id}',
        summary: 'Deleta um assunto pelo ID',
        description: 'Remove um assunto do sistema pelo seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do assunto a ser deletado',
        required: true,
        schema: new SA\Schema(type: 'integer')
    )]
    #[SA\Response(
        response: 200,
        description: 'Assunto deletado com sucesso',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'message', description: 'Mensagem de confirmação', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    #[SA\Response(
        response: 404,
        description: 'Assunto não encontrado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(property: 'success', description: 'Indica se a operação foi bem-sucedida', type: 'boolean'),
                        new SA\Property(property: 'message', description: 'Mensagem de erro', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    public function destroy(int $id)
    {
        $deleted = $this->subjectService->deleteSubject($id);

        if (!$deleted) {
            return $this->response->json(['success' => false, 'message' => 'Assunto não encontrado'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Assunto deletado com sucesso']);
    }
}
