<?php
use Inhere\Route\Router;

$router = new Router();

$router->get('/index', 'Article@index');

$router->group('/article', function ($router) {
    $router->get('/', 'Article@index');
    $router->get('/index', 'Article@index');
    $router->get('read/{id}', 'Article@read');
});

$router->group('/tool', function ($router) {
    $router->get('/uploadImage', 'Tool@uploadImage');
});
