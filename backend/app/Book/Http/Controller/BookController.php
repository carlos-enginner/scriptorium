<?php

namespace App\Book\Http\Controller;

use App\Book\UseCase\CreateBookUseCase;
use App\Book\UseCase\DeleteBookUseCase;
use App\Book\UseCase\GetAllBookUseCase;
use App\Book\UseCase\UpdateBookUseCase;
use App\Request\BookRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Hyperf\Swagger\Annotation as SA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

#[SA\Server('v2')]
#[SA\HyperfServer(name: 'http')]
#[Controller(prefix: '/v2')]
class BookController
{
    #[Inject]
    protected CreateBookUseCase $createBookUseCase;

    #[Inject]
    protected DeleteBookUseCase $deleteBookUseCase;

    #[Inject]
    protected GetAllBookUseCase $getAllBookUseCase;

    #[Inject]
    protected UpdateBookUseCase $updateBookUseCase;

    #[Inject]
    protected ResponseFactory $response;

    // #[Inject]
    // private LoggerInterface $logger;

    #[SA\Get(
        path: '/v2/books',
        summary: 'Lista os livros',
        description: 'Retorna todos os livros ou filtra por título se o parâmetro "title" for fornecido',
    )]
    #[SA\Parameter(
        name: 'title',
        in: 'query',
        description: 'Título do livro para filtrar os resultados',
        required: false,
        schema: new SA\Schema(type: 'string'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Lista de livros',
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
                            description: 'Lista de livros',
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
            // $books = $this->getAllBookUseCase->getBooksByTitle($title);
        } else {
            $books = $this->getAllBookUseCase->execute();
        }

        return $this->response->json([
            'success' => true,
            'data' => $books,
        ]);
    }

    // #[SA\Get(
    //     path: '/v2/books/{id}',
    //     summary: 'Retorna um livro pelo ID',
    //     description: 'Retorna as informações de um livro com base no seu ID',
    // )]
    // #[SA\Parameter(
    //     name: 'id',
    //     in: 'path',
    //     description: 'ID do livro a ser retornado',
    //     required: true,
    //     schema: new SA\Schema(type: 'integer'),
    // )]
    // #[SA\Response(
    //     response: 200,
    //     description: 'Livro encontrado',
    //     content: [
    //         new SA\MediaType(
    //             mediaType: 'application/json',
    //             schema: new SA\Schema(
    //                 properties: [
    //                     new SA\Property(
    //                         property: 'success',
    //                         description: 'Indica se a operação foi bem-sucedida',
    //                         type: 'boolean',
    //                     ),
    //                     new SA\Property(
    //                         property: 'data',
    //                         description: 'Informações do livro',
    //                         type: 'object',
    //                     ),
    //                 ],
    //             ),
    //         ),
    //     ],
    // )]
    // #[SA\Response(
    //     response: 404,
    //     description: 'Livro não encontrado',
    //     content: [
    //         new SA\MediaType(
    //             mediaType: 'application/json',
    //             schema: new SA\Schema(
    //                 properties: [
    //                     new SA\Property(
    //                         property: 'success',
    //                         description: 'Indica se a operação foi bem-sucedida',
    //                         type: 'boolean',
    //                     ),
    //                     new SA\Property(
    //                         property: 'message',
    //                         description: 'Mensagem de erro',
    //                         type: 'string',
    //                     ),
    //                 ],
    //             ),
    //         ),
    //     ],
    // )]
    // public function show(int $id)
    // {
    //     $book = $this->bookService->getBookById($id);
    //     if (! $book) {
    //         return $this->response->json(['success' => false, 'message' => 'Book not found'], 404);
    //     }
    //     return $this->response->json(['success' => true, 'data' => $book]);
    // }

    #[SA\Post(
        path: '/v2/books',
        summary: 'Cria um novo livro',
        description: 'Adiciona um novo livro ao sistema',
    )]
    #[SA\RequestBody(
        description: 'Dados do livro a ser criado',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'title',
                            description: 'Título do livro',
                            type: 'string',
                        ),
                        new SA\Property(
                            property: 'publisher',
                            description: 'Editora do livro',
                            type: 'string',
                        ),
                        new SA\Property(
                            property: 'edition',
                            description: 'Editora do livro',
                            type: 'integer',
                        ),
                        new SA\Property(
                            property: 'publication_year',
                            description: 'Ano de publicação',
                            type: 'integer',
                        ),
                        new SA\Property(
                            property: 'price',
                            description: 'Preço do livro',
                            type: 'float',
                        ),
                        new SA\Property(
                            property: 'authors',
                            description: 'Autores do livro',
                            type: 'array',
                            items: new SA\Items(type: 'object'),
                        ),
                        new SA\Property(
                            property: 'subjects',
                            description: 'Assuntos do livro',
                            type: 'array',
                            items: new SA\Items(type: 'object'),
                        ),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 201,
        description: 'Livro criado com sucesso',
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
                            description: 'Informações do livro criado',
                            type: 'object',
                        ),
                    ],
                ),
            ),
        ],
    )]
    public function store(BookRequest $request)
    {
        try {
            $data = $request->validated();
            $book = $this->createBookUseCase->execute($data);
            // $this->logger->info('[BookController::store] - Livro criado com sucesso', ['info' => $book]);
            return $this->response->json(['success' => true, 'data' => $book], 201);
        } catch (Throwable $error) {
            // $this->logger->error('[BookController::store]', ['error' => $error]);
            $this->response->json($error);
        }
    }

    #[SA\Put(
        path: '/v2/books/{id}',
        summary: 'Atualiza as informações de um livro',
        description: 'Atualiza os dados de um livro existente com base no seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do livro a ser atualizado',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\RequestBody(
        description: 'Dados atualizados do livro',
        content: [
            new SA\MediaType(
                mediaType: 'application/json',
                schema: new SA\Schema(
                    properties: [
                        new SA\Property(
                            property: 'title',
                            description: 'Título do livro',
                            type: 'string',
                        ),
                        new SA\Property(property: 'author', description: 'Autor do livro', type: 'string'),
                        new SA\Property(property: 'year', description: 'Ano de publicação', type: 'integer'),
                        new SA\Property(property: 'price', description: 'Preço do livro', type: 'float'),
                    ],
                ),
            ),
        ],
    )]
    #[SA\Response(
        response: 200,
        description: 'Livro atualizado com sucesso',
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
        description: 'Livro não encontrado',
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
        $updated = $this->updateBookUseCase->execute($id, $data);

        if (! $updated) {
            return $this->response->json(['success' => false, 'message' => 'Book not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Book updated successfully']);
    }

    #[SA\Delete(
        path: '/v2/books/{id}',
        summary: 'Deleta um livro pelo ID',
        description: 'Remove um livro do sistema pelo seu ID',
    )]
    #[SA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID do livro a ser deletado',
        required: true,
        schema: new SA\Schema(type: 'integer'),
    )]
    #[SA\Response(
        response: 200,
        description: 'Livro deletado com sucesso',
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
        description: 'Livro não encontrado',
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
        $deleted = $this->deleteBookUseCase->execute($id);

        if (! $deleted) {
            return $this->response->json(['success' => false, 'message' => 'Book not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Book deleted successfully']);
    }
}
