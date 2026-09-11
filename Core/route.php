<?php

$router->add('GET','/','InicioController@index');
$router->add('GET','/cliente','ClienteController@index');
$router->add('GET','/cliente/listar','ClienteController@index');
$router->add('GET','/cliente/cadastro','ClienteController@cadastro');
$router->add('POST','/cliente/cadastro','ClienteController@cadastro');
$router->add('GET','/cliente/exclusao','ClienteController@exclusao');
$router->add('GET','/admin','InicioController@notFound');
$router->add('GET','/login','LoginController@index');
$router->add('POST','/login','LoginController@index');
$router->add('GET','/logout','LoginController@logout');
