<?php

require __DIR__ . '/../vendor/autoload.php';

(new \Dotenv\Loader(__DIR__.'/.env', false))->setEnvironmentVariable('APP_ENV', \Venta\Framework\Application::ENV_TEST);