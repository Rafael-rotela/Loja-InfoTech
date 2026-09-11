<?php

session_start();

require_once 'config.php';
require_once 'autoload.php';
require_once 'Core/Router.php';
use InfoTech\Core\Router;

$router = new Router();
require 'Core/route.php';

$router->dispatch();
