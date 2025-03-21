<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\Di\Annotation\Inject;
use App\Service\BookService;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;

#[Controller]
class BookController
{
    #[Inject]
    protected BookService $bookService;

    #[Inject]
    protected ResponseFactory $response;

    #[GetMapping(path: "books")]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $title = $queryParams['title'] ?? null;

        $books = [];

        if ($title) {
            $books = $this->bookService->getBooksByTitle($title);
        } else {
            $books = $this->bookService->getAllBooks();
        }

        return $this->response->json([
            'success' => true,
            'data' => $books
        ]);
    }

    #[GetMapping(path: "books/{id}")]
    public function show(int $id)
    {
        $book = $this->bookService->getBookById($id);
        if (!$book) {
            return $this->response->json(['success' => false, 'message' => 'Book not found'], 404);
        }
        return $this->response->json(['success' => true, 'data' => $book]);
    }

    #[PostMapping(path: "books")]
    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $book = $this->bookService->createBook($data);

        return $this->response->json(['success' => true, 'data' => $book], 201);
    }

    #[PutMapping(path: "books/{id}")]
    public function update(int $id, ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $updated = $this->bookService->updateBook($id, $data);

        if (!$updated) {
            return $this->response->json(['success' => false, 'message' => 'Book not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Book updated successfully']);
    }

    #[DeleteMapping(path: "books/{id}")]
    public function destroy(int $id)
    {
        $deleted = $this->bookService->deleteBook($id);

        if (!$deleted) {
            return $this->response->json(['success' => false, 'message' => 'Book not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Book deleted successfully']);
    }
}
