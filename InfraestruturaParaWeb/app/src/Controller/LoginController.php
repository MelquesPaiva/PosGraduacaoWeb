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
                return $this->handleResponse(401, ['message' => 'Login não autorizado']);
            }
            if (!password_verify($requestData->password, $result->password)) {
                return $this->handleResponse(401, ['message' => 'Login não autorizado']);
            }
        } catch (\Throwable $error) {
            return $this->handleResponse(500, $this->errorResponse($error));
        }

        $data['token'] = TokenManager::generateTokenForData(['id' => $result->id, 'user_name' => $result->user_name]);

        return $this->handleResponse($statusCode, $data);
    }
}
