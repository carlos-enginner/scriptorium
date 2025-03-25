<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Service;

use App\Repository\AuthorRepository;

class AuthorService
{
    protected AuthorRepository $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllAuthors()
    {
        return $this->repository->getAll();
    }

    public function getAuthorByName(string $author)
    {
        return $this->repository->getAuthorByName($author);
    }

    public function getAuthorById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function createAuthor(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateAuthor(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteAuthor(int $id)
    {
        return $this->repository->delete($id);
    }
}
