<?php

// Require composer generated autoloader.
require __DIR__ . '/../../../vendor/autoload.php';

// Include packages bootstrap files with mock/stub classes/interfaces.
require __DIR__ . '/../../Container/tests/bootstrap.php';
require __DIR__ . '/../../Console/tests/bootstrap.php';
require __DIR__ . '/../../Event/tests/bootstrap.php';

// Register error handler for graceful error display.
$run = new \Whoops\Run();
$run->pushHandler(new \Whoops\Handler\PlainTextHandler())->register();

// Load environment for Kernel tests.
(new \Dotenv\Loader(__DIR__ . '/.env', false))->setEnvironmentVariable('APP_ENV', 'test');