<?php

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Entity\Subject;

interface SubjectRepositoryInterface
{
    public function getAll(): iterable;

    // /**
    //  * Busca subjects com base em uma query textual.
    //  */
    // public function getByName(string $subject);

    /**
     * Busca um subject pelo ID.
     */
    public function getById(int $id): ?Subject;

    /**
     * Cria um novo subject.
     */
    public function create(array $data);

    /**
     * Atualiza um subject existente.
     */
    public function update(int $id, array $data);

    /**
     * Remove um subject pelo ID.
     */
    public function delete(int $id);
}
