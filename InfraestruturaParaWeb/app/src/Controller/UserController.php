<?php

namespace Infraweb\App\Controller;

use Infraweb\App\Controller\Response\ResponseHelper;
use Infraweb\App\DB\MysqlConnection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    use ResponseHelper;

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);

        $userName = $data['user_name'] ?? null;
        $password = $data['password'] ?? null;
        $createdAt = date('Y-m-d H:i:s');

        if (!$userName || !$password) {
            $response->getBody()->write(json_encode(['message' => 'Faltam preencher parametros obrigatórios']));
            return $response->withStatus(400);
        }

        $sql = "INSERT INTO user (user_name, password, created_at) VALUES (:user_name, :password, :created_at)";

        try {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = MysqlConnection::getInstance()->prepare($sql);
            $stmt->bindParam(':user_name', $userName);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':created_at', $createdAt);

            $stmt->execute();

            $response->getBody()->write(json_encode(['message' => 'Usuario criado com sucesso']));
            return $response->withStatus(201);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode($this->errorResponse($e)));
            return $response->withStatus(500);
        }
    }
}
