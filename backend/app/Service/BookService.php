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
        $book = $this->bookRepository->create($data);

        if (!empty($data['authors'])) {
            foreach ($data['authors'] as $authorId) {
                $this->bookAuthorRepository->create([
                    'book_id' => $book->id,
                    'author_id' => $authorId,
                ]);
            }
        }

        if (!empty($data['subjects'])) {
            foreach ($data['subjects'] as $subjectId) {
                $this->bookSubjectRepository->create([
                    'book_id' => $book->id,
                    'subject_id' => $subjectId,
                ]);
            }
        }
    }

    public function updateBook(int $id, array $data)
    {
        try {
            $update = $this->bookRepository->update($id, $data);

            $this->bookSubjectRepository->upsert($id, $data["subjects"]);

            $this->bookAuthorRepository->upsert($id, $data["authors"]);

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
