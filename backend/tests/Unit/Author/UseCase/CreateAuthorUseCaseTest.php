<?php

use PHPUnit\Framework\TestCase;
use App\Author\UseCase\CreateAuthorUseCase;
use App\Author\Domain\Repository\AuthorRepositoryInterface;

class CreateAuthorUseCaseTest extends TestCase
{
    private $authorRepository;
    private $createAuthorUseCase;

    protected function setUp(): void
    {
        $this->authorRepository = Mockery::mock(AuthorRepositoryInterface::class);
        $this->createAuthorUseCase = new CreateAuthorUseCase($this->authorRepository);
    }

    public function testCreateAuthor()
    {
        $authorData = [
            'name' => 'John Doe',
            'email' => 'sds',
            'password' => 'password123',
            'bio' => 'Author bio',
            'website' => 'https://example.com',
        ];
        $this->authorRepository
            ->shouldReceive('create')
            ->once()
            ->with($authorData)
            ->andReturn(true);
        $result = $this->createAuthorUseCase->execute($authorData);
        $this->assertTrue($result);
    }
}

