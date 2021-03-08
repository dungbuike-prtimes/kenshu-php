<?php

$router->get('/', "homeController@index");
$router->get('/auth/login', "UserController@login");
$router->post('/auth/login', "UserController@auth");
$router->get('/auth/logout', "UserController@logout", AUTH_REQUIRED);
$router->get('/auth/register', "UserController@register");
$router->post('/auth/register', "UserController@create");


$router->get('/tag/create', "TagController@create", AUTH_REQUIRED);
$router->post('/tag/create', "TagController@store", AUTH_REQUIRED);
$router->get('/tag/edit/{id}', "TagController@edit", AUTH_REQUIRED);
$router->post('/tag/edit/{id}', "TagController@update", AUTH_REQUIRED);

$router->get('/post/index', "PostController@index", AUTH_REQUIRED);
$router->get('/post/create', "PostController@create", AUTH_REQUIRED);
$router->post('/post/create', "PostController@store", AUTH_REQUIRED);
