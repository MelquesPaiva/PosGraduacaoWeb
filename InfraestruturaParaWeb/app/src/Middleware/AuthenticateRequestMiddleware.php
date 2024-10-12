<?php

namespace Infraweb\App\Middleware;

use Infraweb\App\Application\TokenManager;
use Infraweb\App\Controller\Response\ResponseHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticateRequestMiddleware implements MiddlewareInterface
{
    use ResponseHelper;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = $request->getHeader('Authorization')[0] ?? null;
        if (empty($authorization)) {
            return $this->handleResponse(401, ['message' => 'Não autorizado']);
        }

        $tokenData = TokenManager::getDataFromToken($authorization);
        if (empty($tokenData)) {
            return $this->handleResponse(401, ['message' => 'Token inválido']);
        }

        $userId = $tokenData['id'] ?? null;
        if (empty($userId)) {
            return $this->handleResponse(401, ['message' => 'Dados de token inválido']);
        }

        return $handler->handle($request);
    }
}
