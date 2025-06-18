<?php

namespace GaspardV\PhpShell;

require_once dirname(__DIR__) . "/vendor/autoload.php";

use GaspardV\PhpShell\Router\Builders;

$sseBuilder = new Builders\Route();
$routerBuilder = new Builders\Router();

$sse = $sseBuilder
    ->setRoute("sse")
    ->setCallback(fn() => print "lol")
    ->build();

$routerBuilder->addRoute($sse);
$router = $routerBuilder->build();
