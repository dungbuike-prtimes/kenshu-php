<?php

$router->get('/', "homeController@index");
$router->get('/auth/login', "UserController@login");
$router->post('/auth', "UserController@auth");
$router->get('/auth/logout', "UserController@logout", AUTH_REQUIRED);
$router->get('/auth/register', "UserController@register");
$router->post('/auth/register', "UserController@create");


$router->get('/tags/create', "TagController@create", AUTH_REQUIRED);
$router->post('/tags', "TagController@store", AUTH_REQUIRED);
$router->get('/tags/{id}/edit', "TagController@edit", AUTH_REQUIRED);
$router->post('/tags/{id}', "TagController@update", AUTH_REQUIRED);

$router->get('/posts', "PostController@index", AUTH_REQUIRED);
$router->get('/posts/create', "PostController@create", AUTH_REQUIRED);
$router->post('/posts', "PostController@store", AUTH_REQUIRED);
$router->get('/posts/{id}/edit', "PostController@edit", AUTH_REQUIRED);
$router->get('/posts/{id}', "PostController@show", AUTH_REQUIRED);
$router->put('/posts/{id}', "PostController@update", AUTH_REQUIRED);
$router->delete('/posts/{id}', "PostController@delete", AUTH_REQUIRED);
