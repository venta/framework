<?php

require __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/../../Container/tests/bootstrap.php';

$run = new \Whoops\Run();
$run->pushHandler(new \Whoops\Handler\PlainTextHandler())->register();

(new \Dotenv\Loader(__DIR__ . '/.env', false))->setEnvironmentVariable('APP_ENV', 'test');