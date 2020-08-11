<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // $app->get('/', function (Request $request, Response $response){
    //     return $this->view->render($response, 'main.twig');
    // })->setName('root');

    $container = $app->getContainer();
    $app->group("", function (RouteCollectorProxy $view) {
       
        $view->get("/", function($request, $response, $args) {
            return $this->get('view')->render($response, 'main.twig');        
        });

    })->add($container->get('viewMiddleware'));

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
