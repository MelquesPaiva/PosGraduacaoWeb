<?php

namespace Infraweb\App\Controller\Response;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

trait ResponseHelper
{
    private function errorResponse(\Throwable $error): array
    {
        return [
            'status' => 'error',
            'message' => 'Ocorreu um error',
            'trace_message' => $error->getMessage(),
        ];
    }

    private function handleResponse(int $statusCode, \stdClass|array $data): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode($data));
        $response->withStatus($statusCode);

        return $response;
    }
}
