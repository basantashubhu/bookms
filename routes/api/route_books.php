<?php

/**@var \Laravel\Lumen\Routing\Router $router*/


$router->group(['prefix' => 'books', 'namespace' => 'Book'], function () use($router) {
    $router->get('/', 'BookApiController@allBooks');

    $router->group(['middleware' => 'admin'], function () use($router){
        $router->post('/', 'BookApiAdminController@store');
        $router->get('/{book}', 'BookApiAdminController@findBook');
        $router->post('/{book}/update', 'BookApiAdminController@update');
        $router->delete('/{book}/delete', 'BookApiAdminController@delete');
    });

    $router->group(['middleware' => 'api'], function () use ($router){
        $router->get('/download/{file}', [
            'as' => 'book.url',
            'uses' => 'BookApiController@downloadPdf'
        ]);
    });
});
