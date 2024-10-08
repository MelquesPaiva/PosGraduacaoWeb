<?php

namespace Infraweb\App\Controller;

use Infraweb\App\DB\MysqlConnection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class PeopleController
{
    public function get(): ResponseInterface
    {
        $statusCode = 200;
        try {
            $data = MysqlConnection
                ::getInstance()
                ->query("SELECT * FROM People", \PDO::FETCH_ASSOC)
                ->fetchAll();
        } catch (\Throwable $error) {
            $statusCode = 500;
            $data = $this->errorResponse($error);
        }

        return $this->handleResponse($statusCode, $data);
    }

    public function getById(int $id): ResponseInterface
    {
        $statusCode = 200;
        try {
            $stmt = MysqlConnection::getInstance()->prepare("SELECT * FROM People WHERE id = :id");
            $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchObject();
        } catch (\Throwable $error) {
            $statusCode = 500;
            $data = $this->errorResponse($error);
        }

        return $this->handleResponse($statusCode, $data);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $statusCode = 200;
        $data = ['message' => 'Success'];
        try {
            $stmt = MysqlConnection::getInstance()->prepare("DELETE FROM People WHERE id = :id");
            $stmt->bindParam('id', $args['id'], \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\Throwable $error) {
            $statusCode = 500;
            $data = $this->errorResponse($error);
        }

        return $this->handleResponse($statusCode, $data);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $statusCode = 200;
        $data = ['message' => 'Success'];
        try {
            $requestData = json_decode($request->getBody()->getContents());
            $stmt = MysqlConnection::getInstance()->prepare(
                "UPDATE People SET name = :name, age = :age, cell_phone = :cell_phone WHERE id = :id"
            );
            $stmt->execute([
                'name' => $requestData->name ?? null,
                'age' => $requestData->age ?? null,
                'cell_phone' => $requestData->cell_phone ?? null,
                'id' => $args['id'],
            ]);
        } catch (\Throwable $error) {
            $statusCode = 500;
            $data = $this->errorResponse($error);
        }

        return $this->handleResponse($statusCode, $data);
    }

    public function save(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $statusCode = 200;
        $data = ['message' => 'Success'];
        try {
            $requestData = json_decode($request->getBody()->getContents());
            $stmt = MysqlConnection::getInstance()->prepare(
                "INSERT INTO People (name, age, cell_phone) VALUES (:name, :age, :cell_phone)"
            );
            $stmt->execute([
                'name' => $requestData->name ?? null,
                'age' => $requestData->age ?? null,
                'cell_phone' => $requestData->cell_phone ?? null,
            ]);
        } catch (\Throwable $error) {
            $statusCode = 500;
            $data = $this->errorResponse($error);
        }

        return $this->handleResponse($statusCode, $data);
    }

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
