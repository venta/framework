<?php declare(strict_types = 1);

namespace Abava\Routing\Dispatcher\Factory;

use Abava\Routing\Contract\Dispatcher\DispatcherFactory;
use FastRoute\Dispatcher;

/**
 * Class GroupCountBasedFactory
 *
 * @package Abava\Routing\Dispatcher\Factory
 */
class GroupCountBasedDispatcherFactory implements DispatcherFactory
{

    /**
     * @inheritDoc
     */
    public function create(array $data): Dispatcher
    {
        return new Dispatcher\GroupCountBased($data);
    }

}