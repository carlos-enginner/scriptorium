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

namespace Tests\Feature;

use App\Controller\BookController;
use App\Service\BookService;
use Faker\Factory as Faker;
use GuzzleHttp\Psr7\Response;
use Hyperf\Context\ApplicationContext;
use Hyperf\HttpMessage\Server\Request;
use Hyperf\Testing\TestCase;
use Mockery;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

use function Hyperf\Support\now;

/**
 * @coversDefaultClass \App\Controller\BookController
 * @internal
 */
class BookControllerTest extends TestCase
{
    protected $faker;

    protected $clientMock;

    private $controller;

    private $bookService;

    private $logger;

    private $response;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = ApplicationContext::getContainer();
        $this->faker = Faker::create();

        $this->clientMock = Mockery::mock(ClientInterface::class);
        $this->container->set(ClientInterface::class, $this->clientMock);

        $this->bookService = Mockery::mock(BookService::class);
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->response = Mockery::mock(\Hyperf\HttpServer\Response::class);

        $this->controller = new BookController(
            $this->bookService,
            $this->logger,
            $this->response
        );
    }

    /**
     * @coversNothing
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @covers ::store
     */
    public function testCreateBookSuccess()
    {
        $payload = [
            'price' => $this->faker->randomFloat(2, 1, 100),
            'publication_year' => (string) $this->faker->year(),
            'edition' => $this->faker->numberBetween(1, 10),
            'publisher' => $this->faker->company(),
            'title' => $this->faker->sentence(3),
            'authors' => [1],
            'subjects' => [1],
        ];

        $mockResponse = new Response(201, [], json_encode([
            'data' => array_merge($payload, ['id' => 1, 'created_at' => now(), 'updated_at' => now()]),
        ]));

        $this->clientMock->shouldReceive('sendRequest')
            ->once()
            ->andReturn($mockResponse);

        $response = $this->clientMock->sendRequest(new Request('POST', '/books', [], json_encode($payload)));

        $this->assertSame(201, $response->getStatusCode());

        $responseData = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertIsInt($responseData['data']['id']);

        return $responseData['data']['id'];
    }
}
