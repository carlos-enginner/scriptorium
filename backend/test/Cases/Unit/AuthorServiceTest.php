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

namespace Tests\Service;

use App\Model\Author;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\AuthorService
 * @internal
 */
class AuthorServiceTest extends TestCase
{
    protected AuthorService $authorService;

    protected $authorRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorRepositoryMock = Mockery::mock(AuthorRepository::class);

        $this->authorService = new AuthorService($this->authorRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @covers ::getAllAuthors
     */
    public function testGetAllAuthors()
    {
        $this->authorRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn(['Author 1', 'Author 2']);

        $authors = $this->authorService->getAllAuthors();

        $this->assertEquals(['Author 1', 'Author 2'], $authors);
    }

    /**
     * @covers ::getAuthorByName
     */
    public function testGetAuthorByName(): void
    {
        $expectedAuthor = new Author(['name' => 'Author 1']);
        $this->authorRepositoryMock->shouldReceive('getAuthorByName')
            ->once()
            ->with('Author 1')
            ->andReturn($expectedAuthor);

        $excepted = $this->authorService->getAuthorByName('Author 1');

        $this->assertEquals($expectedAuthor['name'], $excepted->name);
    }

    /**
     * @covers ::getAuthorByName
     */
    public function testGetAuthorByIdReturnsAuthorWhenValidIdProvided(): void
    {
        $authorId = 1;
        $expectedAuthor = new Author(['id' => $authorId]);

        $this->authorRepositoryMock->shouldReceive('findById')
            ->once()
            ->with($authorId)
            ->andReturn($expectedAuthor);

        $result = $this->authorService->getAuthorById($authorId);

        $this->assertEquals($expectedAuthor, $result);
    }

    /**
     * @covers ::getAuthorById
     */
    public function testGetAuthorById()
    {
        $this->authorRepositoryMock->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn(['id' => 1, 'name' => 'Author 1']);

        $author = $this->authorService->getAuthorById(1);

        $this->assertEquals(['id' => 1, 'name' => 'Author 1'], $author);
    }

    /**
     * @covers ::createAuthor
     */
    public function testCreateAuthor()
    {
        $this->authorRepositoryMock->shouldReceive('create')
            ->once()
            ->with(['name' => 'Author 1'])
            ->andReturn(true);

        $result = $this->authorService->createAuthor(['name' => 'Author 1']);

        $this->assertTrue($result);
    }

    /**
     * @covers ::updateAuthor
     */
    public function testUpdateAuthor()
    {
        $this->authorRepositoryMock->shouldReceive('update')
            ->once()
            ->with(1, ['name' => 'Updated Author'])
            ->andReturn(true);

        $result = $this->authorService->updateAuthor(1, ['name' => 'Updated Author']);

        $this->assertTrue($result);
    }

    /**
     * @covers ::deleteAuthor
     */
    public function testDeleteAuthor()
    {
        $this->authorRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $this->authorService->deleteAuthor(1);

        $this->assertTrue($result);
    }
}
