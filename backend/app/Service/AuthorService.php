<?php

namespace App\Service;

use App\Model\Author;
use App\Repository\AuthorRepository;
use Hyperf\Di\Annotation\Inject;

class AuthorService
{
    #[Inject]
    protected AuthorRepository $repository;

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
