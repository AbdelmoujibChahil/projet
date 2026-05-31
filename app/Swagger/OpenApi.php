<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Restaurant API",
 *     version="1.0.0",
 *     description="API Documentation"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Local server"
 * )
 */
class OpenApi
{
}