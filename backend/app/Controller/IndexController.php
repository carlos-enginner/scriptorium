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

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use OpenApi\Annotations as OA;


/**
 * @AutoController()
 * @OA\Info(
 *     title="Minha API Hyperf",
 *     version="1.0",
 *     description="API de exemplo com Swagger"
 * )
 */

class IndexController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Lista todos os usuários",
     *     tags={"Users"},
     *     @OA\Response(response="200", description="Lista de usuários")
     * )
     */
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
