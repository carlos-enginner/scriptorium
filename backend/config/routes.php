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

use App\Controller\AuthorController;
use Hyperf\HttpServer\Router\Router;
use App\Controller\BookController;
use App\Controller\SubjectController;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

# books
Router::get('/books', [BookController::class, 'index']);
Router::get('/books/{id}', [BookController::class, 'show']);
Router::post('/books', [BookController::class, 'store']);
Router::put('/books/{id}', [BookController::class, 'update']);
Router::delete('/books/{id}', [BookController::class, 'destroy']);

# subjects
Router::get('/subjects', [SubjectController::class, 'index']);
Router::get('/subjects/{id}', [SubjectController::class, 'show']);
Router::post('/subjects', [SubjectController::class, 'store']);
Router::put('/subjects/{id}', [SubjectController::class, 'update']);
Router::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

# author
Router::get('/authors', [AuthorController::class, 'index']);
Router::get('/authors/{id}', [AuthorController::class, 'show']);
Router::post('/authors', [AuthorController::class, 'store']);
Router::put('/authors/{id}', [AuthorController::class, 'update']);
Router::delete('/authors/{id}', [AuthorController::class, 'destroy']);
