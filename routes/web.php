<?php

/** @var \Laravel\Lumen\Routing\Router $router */


use Illuminate\Support\Facades\File;

$router->get('/', function () use ($router) {
    return view('documentation');
});

foreach (File::allFiles(base_path('routes/api')) as $file) {
    $router->group(['prefix' => 'api'], function () use ($router, $file) {
        require_once $file->getPathname();
    });
}

//dd($router->getRoutes());
