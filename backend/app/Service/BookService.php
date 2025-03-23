<?php

namespace App\Service;

use App\Repository\BookAuthorRepository;
use App\Repository\BookRepository;
use App\Repository\BookSubjectRepository;
use Hyperf\Di\Annotation\Inject;
use LogicException;
use Throwable;

class BookService
{
    #[Inject]
    protected BookRepository $bookRepository;
    #[Inject]
    protected BookAuthorRepository $bookAuthorRepository;
    #[Inject]
    protected BookSubjectRepository $bookSubjectRepository;

    public function getAllBooks()
    {
        return $this->bookRepository->getAll();
    }

    public function getBooksByTitle(string $title)
    {
        return $this->bookRepository->getBooksByTitle($title);
    }

    public function getBookById(int $id)
    {
        $book = $this->bookRepository->findById($id);

        $book['subjects'] = $this->bookSubjectRepository->getSubjectsByBookId($id);

        $book['authors'] = $this->bookAuthorRepository->getAuthorsByBookId($id);

        return $book;
    }

    public function createBook(array $data)
    {
        try {
            $book = $this->bookRepository->create($data);

            if (!empty($data['authors'])) {
                $this->bookAuthorRepository->upsert($book->id, $data["authors"]);
            }

            if (!empty($data['subjects'])) {
                $this->bookSubjectRepository->upsert($book->id, $data["subjects"]);
            }
            return $book;
        } catch (Throwable $e) {
            throw new LogicException($e->getMessage());
        }
    }

    public function updateBook(int $id, array $data)
    {
        try {
            $update = $this->bookRepository->update($id, $data);
            if (!empty($data['authors'])) {
                $this->bookAuthorRepository->upsert($id, $data["authors"]);
            }

            if (!empty($data['subjects'])) {
                $this->bookSubjectRepository->upsert($id, $data["subjects"]);
            }
            return $update;
        } catch (Throwable $e) {
            throw new LogicException($e->getMessage());
        }
    }

    public function deleteBook(int $id)
    {
        return $this->bookRepository->delete($id);
    }
}
