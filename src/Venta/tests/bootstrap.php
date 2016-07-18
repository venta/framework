<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/abava/container/tests/StubClasses.php';

(new \Dotenv\Loader(__DIR__ . '/.env', false))->setEnvironmentVariable('APP_ENV', \Venta\Application::ENV_TEST);

interface Router{

    public function dispatch();

}