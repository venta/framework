<?php

return [
    SampleExtension::class
];

class SampleExtension {

    public function bindings($app) {}

    public function errors($error_handler) {}

    public function terminate($app) {}
}