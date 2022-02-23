<?php

/**@var \Laravel\Lumen\Routing\Router $router*/


$router->group(['namespace' => 'Auth'], function () use($router) {
    $router->post('login', 'AuthApiController@login');
    $router->post('register', 'AuthApiController@register');
});
