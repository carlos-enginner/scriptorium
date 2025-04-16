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
return [
    \App\Author\Domain\Repository\AuthorRepositoryInterface::class => \App\Author\Infra\Repository\AuthorRepository::class,
    \App\Subject\Domain\Repository\SubjectRepositoryInterface::class => \App\Subject\Infra\Repository\SubjectRepository::class,
    \App\Book\Domain\Repository\BookRepositoryInterface::class => \App\Book\Infra\Repository\BookRepository::class,
];
