<?php
use Inhere\Route\Router;

$router = new Router();
// 路由组
$router->config([
    'ignoreLastSlash'     => true,
    'autoRoute'           => 1,
    'controllerNamespace' => '\\',
    'controllerSuffix'    => 'Controller',
    'actionSuffix'        => 'Action',
]);

$router->group('/article', function ($router) {
    $router->get('/', 'ArticleController::class@index');
    $router->get('/index', 'ArticleController::class@index');
    $router->get('/read/{id}', 'ArticleController::class@read');
});
