<?php

namespace App\Author\Domain\Repository;

// use App\Actor\Domain\Entity\Actor;

interface AuthorRepositoryInterface
{
    public function getAll(): iterable;

    /**
     * Busca autores com base em uma query textual.
     */
    public function getAuthorByName(string $author);

    /**
     * Busca um autor pelo ID.
     */
    public function findById(int $id);

    /**
     * Cria um novo autor.
     */
    public function create(array $data);

    /**
     * Atualiza um autor existente.
     */
    public function update(int $id, array $data);

    /**
     * Remove um autor pelo ID.
     */
    public function delete(int $id);
}
