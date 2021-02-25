<?php
require_once(__DIR__ . '/src/autoload.php');

session_start();

$router = new Router();
$router->run();