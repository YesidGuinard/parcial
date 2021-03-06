<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\MateriaController;
use App\Controllers\UsuariosController;
use App\Middleware\AuthMiddleware;
use App\Middleware\User\LoginValidateMiddleware;
use App\Middleware\User\ABMValidateMiddleware;
use App\Middleware\User\AdminTypeMiddleware;
use App\Middleware\User\MateriaProfesorValidaMiddleware;

return function ($app) {

    $app->group('/', function (RouteCollectorProxy $group) {
        $group->post('login[/]', UsuariosController::class . ':login');
    })->add(new LoginValidateMiddleware());

    $app->group('/', function (RouteCollectorProxy $group) {
        $group->post('usuario[/]', UsuariosController::class . ':add');
    })->add(new ABMValidateMiddleware());

    $app->group('/materias', function (RouteCollectorProxy $group) {
        $group->post('[/]', MateriaController::class . ':addMateria')->add(MateriaProfesorValidaMiddleware::class);
        $group->put('/{id}/{profesor}[/]', MateriaController::class . ':AsignaProfe');
    })->add(AdminTypeMiddleware::class)->add(new AuthMiddleware());

    $app->group('/materias', function (RouteCollectorProxy $group) {
        $group->get('/{id}[/]', MateriaController::class . ':getByID');
    })->add(new AuthMiddleware());

 };
