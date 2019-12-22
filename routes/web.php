<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->get('/key', function() {
//     return \Illuminate\Support\Str::random(32);
// });


$router->group(
    ['middleware' => 'jwt.auth'], 
    function() use ($router) {
        $router->get('users', function() {
            $users = \App\User::all();
            return response()->json($users);
        });
    }
);

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('auth/register', 'AuthController@register');
    $router->post('auth/login', 'AuthController@login');

    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        $router->post('auth/logout', 'AuthController@logout');

        $router->get('/categories', 'CategoriesController@index');
        $router->get('/categories/{id}', 'CategoriesController@show');
        $router->post('/categories', 'CategoriesController@store');
        $router->put('/categories/{id}', 'CategoriesController@update');
        $router->delete('/categories/{id}', 'CategoriesController@destroy');

        $router->get('/todos', 'TodosController@index');
        $router->get('/todos/{id}', 'TodosController@show');
        $router->post('/todos', 'TodosController@store');
        $router->put('/todos/{id}', 'TodosController@update');
        $router->delete('/todos/{id}', 'TodosController@destroy');
        $router->post('/todos/filter', 'TodosController@filter');
    });

});