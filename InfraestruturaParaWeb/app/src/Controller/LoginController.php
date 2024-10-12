<?php

namespace Infraweb\App\Controller;

use Infraweb\App\Application\TokenManager;
use Infraweb\App\Controller\Response\ResponseHelper;
use Infraweb\App\DB\MysqlConnection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController
{
    use ResponseHelper;

    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $statusCode = 200;
        $data = ['message' => 'Success'];
        try {
            $requestData = json_decode($request->getBody()->getContents());
            $stmt = MysqlConnection::getInstance()->prepare(
                "SELECT * FROM user WHERE user_name = :user_name"
            );
            $stmt->bindParam('user_name', $requestData->user_name);
            $stmt->execute();
            $result = $stmt->fetchObject();
            if (!$result) {
                $response->getBody()->write(json_encode(['message' => 'Login não autorizado']));
                return $response->withStatus(401);
            }
            if (!password_verify($requestData->password, $result->password)) {
                $response->getBody()->write(json_encode(['message' => 'Login não autorizado']));
                return $response->withStatus(401);
            }
        } catch (\Throwable $error) {
            $response->getBody()->write(json_encode($this->errorResponse($error)));
            return $response->withStatus(500);
        }

        $data['token'] = TokenManager::generateTokenForData(['id' => $result->id, 'user_name' => $result->user_name]);

        return $this->handleResponse($statusCode, $data);
    }
}
