<?php

require_once 'vendor/autoload.php';

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__. '/../');
$dotenv->load();

$container = require_once __DIR__ . '/container.php';
return ConsoleRunner::createHelperSet($container->get(EntityManagerInterface::class));
