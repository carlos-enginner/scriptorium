<?php

namespace App\Book\Domain\Repository;

interface BookRepositoryInterface
{
    /**
     * Lista todos os livros.
     */
    public function getAll(): iterable;

    // /**
    //  * Busca um livro pelo ID.
    //  */
    // public function findById(int $id);

    /**
     * Cria um novo livro.
     */
    public function create(array $data);

    /**
     * Atualiza um livro existente.
     */
    public function update(int $id, array $data);

    /**
     * Remove um livro pelo ID.
     */
    public function delete(int $id);
}
