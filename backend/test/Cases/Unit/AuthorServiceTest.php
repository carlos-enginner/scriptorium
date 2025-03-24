<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Model\Author;
use App\Service\AuthorService;
use App\Repository\AuthorRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\AuthorService
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
    public function test_get_author_by_name(): void
    {
        $expectedAuthor = new Author(["name" => "Author 1"]);
        $this->authorRepositoryMock->shouldReceive('getAuthorByName')
            ->once()
            ->with('Author 1')
            ->andReturn($expectedAuthor);

        $excepted = $this->authorService->getAuthorByName('Author 1');

        $this->assertEquals($expectedAuthor["name"], $excepted->name);
    }

    /**
     * @covers ::getAuthorByName
     */

    public function test_get_author_by_id_returns_author_when_valid_id_provided(): void
    {
        $authorId = 1;
        $expectedAuthor = new Author(["id" => $authorId]);

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
    public function test_create_author()
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


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
