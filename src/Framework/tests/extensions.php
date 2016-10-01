<?php

use App\Provider\AppServiceProvider;

class TestServiceProvider extends \Venta\ServiceProvider\AbstractServiceProvider
{
    public function boot()
    {

    }
}

return [
    AppServiceProvider::class,
];
