<?php

namespace App\Service;

use App\Model\Book;
use App\Repository\BookAuthorRepository;
use App\Repository\BookRepository;
use App\Repository\BookSubjectRepository;
use Hyperf\Di\Annotation\Inject;

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
        return Book::where('title', 'ILIKE', "%{$title}%")->get();
    }

    public function getBookById(int $id)
    {
        return $this->bookRepository->findById($id);
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
        return $this->bookRepository->update($id, $data);
    }

    public function deleteBook(int $id)
    {
        return $this->bookRepository->delete($id);
    }
}
