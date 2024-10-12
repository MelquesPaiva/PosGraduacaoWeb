<?php

use Dotenv\Dotenv;
use Infraweb\App\Controller\LoginController;
use Infraweb\App\Controller\PeopleController;
use Infraweb\App\Controller\UserController;
use Infraweb\App\Middleware\AuthenticateRequestMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$env = Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function (Request $request, \Psr\Http\Server\RequestHandlerInterface $handler) use ($app): Response {
    if ($request->getMethod() === 'OPTIONS') {
        $response = $app->getResponseFactory()->createResponse();
    } else {
        $response = $handler->handle($request);
    }

    $response = $response
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->withHeader('Pragma', 'no-cache');

    if (ob_get_contents()) {
        ob_clean();
    }

    return $response;
});

$app->post('/login', [LoginController::class, 'login']);
$app->post('/user/create', [UserController::class, 'create']);

$app->group('/people', function (RouteCollectorProxy $group) {
    $group->post('', [PeopleController::class, 'save']);
    $group->get('', [PeopleController::class, 'get']);
    $group->get('/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $peopleController = new PeopleController();
        return $peopleController->getById($args['id']);
    });
    $group->delete('/{id:[0-9]+}', [PeopleController::class, 'delete']);
    $group->put('/{id:[0-9]+}', [PeopleController::class, 'update']);
})->addMiddleware(new AuthenticateRequestMiddleware());

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $req->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});

$app->run();
