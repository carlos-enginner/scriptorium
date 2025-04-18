<?php

namespace App\Book\Domain\Repository;

use App\Book\UseCase\DTO\BookDTO;

interface BookRepositoryInterface
{
    /**
     * Lista todos os livros.
     */
    public function getAll(): iterable;

    /**
     * Cria um novo livro.
     */
    public function create(BookDTO $data);

    /**
     * Atualiza um livro existente.
     */
    public function update(int $id, BookDTO $data);

    /**
     * Remove um livro pelo ID.
     */
    public function delete(int $id);

    /**
     * Vincula os authors ao livro
     */
    public function attachAuthors(int $bookId, array $authorIds = []);

    /**
     * Vincula os subjects ao livro
     */
    public function attachSubjects(int $bookId, array $subjectIds = []);
}
