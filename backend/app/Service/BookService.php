<?php

namespace App\Service;

use App\Repository\BookRepository;
use Hyperf\Di\Annotation\Inject;

class BookService
{
    #[Inject]
    protected BookRepository $bookRepository;

    public function getAllBooks()
    {
        return $this->bookRepository->getAll();
    }

    public function getBookById(int $id)
    {
        return $this->bookRepository->findById($id);
    }

    public function createBook(array $data)
    {
        return $this->bookRepository->create($data);
    }

    public function updateBook(int $id, array $data)
    {
        return $this->bookRepository->update($id, $data);
    }

    public function deleteBook(int $id)
    {
        return $this->bookRepository->delete($id);
    }
}
