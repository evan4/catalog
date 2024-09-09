<?php

use Catalog\Router;

$router = new Router();

$router->add('/','HomeController@index');
$router->add('/singup','HomeController@singup');
$router->add('/admin','AdminController@index');

$router->add('/login','AuthController@login', 'post');
$router->add('/register','AuthController@register', 'post');
$router->add('/update','AdminController@updateUser', 'post');
$router->add('/logout','AuthController@logout');

// // ajax requsets
// $router->add('/auth','AdminController@auth', 'post');
// $router->add('/singup','AdminController@singup', 'post');
// $router->add('/user-exists','AdminController@userExists', 'post');

$router->dispatch();
