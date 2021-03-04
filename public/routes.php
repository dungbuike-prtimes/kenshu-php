<?php

$router->get('/auth/login', "authController@login");
$router->get('/auth/logout', "authController@logout", AUTH_REQUIRED);
$router->post('/auth/login', "authController@postLogin");
$router->get('/auth/register', "authController@register");

$router->get('/user/all', "authController@login", AUTH_REQUIRED);
