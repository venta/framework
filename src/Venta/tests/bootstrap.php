<?php

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../Abava/Container/tests/StubClasses.php';

$run = new \Whoops\Run();
$run->pushHandler(new \Whoops\Handler\PlainTextHandler())->register();

(new \Dotenv\Loader(__DIR__ . '/.env', false))->setEnvironmentVariable('APP_ENV', \Venta\Application::ENV_TEST);

interface Router{

    public function dispatch();

}