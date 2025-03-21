<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\Di\Annotation\Inject;
use App\Service\SubjectService;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;

#[Controller]
class SubjectController
{
    #[Inject]
    protected SubjectService $subjectService;

    #[Inject]
    protected ResponseFactory $response;

    #[GetMapping(path: "subject")]
    public function index(): ResponseInterface
    {
        $books = $this->subjectService->getAllSubjects();
        return $this->response->json([
            'success' => true,
            'data' => $books
        ]);
    }

    #[GetMapping(path: "subject/{id}")]
    public function show(int $id)
    {
        $subject = $this->subjectService->getSubjectById($id);
        if (!$subject) {
            return $this->response->json(['success' => false, 'message' => 'Subject not found'], 404);
        }
        return $this->response->json(['success' => true, 'data' => $subject]);
    }

    #[PostMapping(path: "subject")]
    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $book = $this->subjectService->createSubject($data);

        return $this->response->json(['success' => true, 'data' => $book], 201);
    }

    #[PutMapping(path: "subject/{id}")]
    public function update(int $id, ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $updated = $this->subjectService->updateSubject($id, $data);

        if (!$updated) {
            return $this->response->json(['success' => false, 'message' => 'Subject not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Subject updated successfully']);
    }

    #[DeleteMapping(path: "subject/{id}")]
    public function destroy(int $id)
    {
        $deleted = $this->subjectService->deleteSubject($id);

        if (!$deleted) {
            return $this->response->json(['success' => false, 'message' => 'Subject not found'], 404);
        }

        return $this->response->json(['success' => true, 'message' => 'Subject deleted successfully']);
    }
}
