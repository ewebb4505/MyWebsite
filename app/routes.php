<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Routing\RouteCollectorProxy;

require 'dbfunctions.php';

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });
    $container = $app->getContainer();
    $app->get('/me', function (Request $request, Response $response) use ($container) {
    
        $me = getMe($container->get('connection'));
        $body = $response->getBody();
        $body->write(json_encode($me));
        
        return $response;
    })->setName('root');
    
    $app->group("", function (RouteCollectorProxy $view) use ($app){
        $container = $app->getContainer();
        $view->get("/", function($request, $response, $args) use ($container){
            $me = getMe($container->get('connection'));
            
            return $this->get('view')->render($response, 'main.twig', array("data" => $me));        
        });

        
    })->add($container->get('viewMiddleware'));


    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
