<?php
declare(strict_types=1);

use App\Application\Actions\Auth\CreateTokenAuthAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Middleware\JwtAuthenticationMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->group('/auth', function (Group $group) {
        $group->post('/access_token', CreateTokenAuthAction::class);
        $group->get('/access_token', CreateTokenAuthAction::class);
    });

    $app->get('/token-test', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world! Token is right.');
        return $response;
    })->add(JwtAuthenticationMiddleware::class);
};
