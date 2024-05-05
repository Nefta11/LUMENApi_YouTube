<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('/auth/login',[
    'uses'=>'AuthController@authenticate'
]);

$router->post('/users', 'UserController@store');


$router->group(
    ['middleware'=>'jwt.auth'],
    function () use ($router){
        $router->get('/users', 'UserController@index');
        $router->get('/categories', 'CategoryController@index');
    }
);
//Siuu
//Estoy usando choco en windows para la actualizacion y descarga de eklementos via terminal
//ejemplo choco uninstall php