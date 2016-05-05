<?php declare(strict_types = 1);

namespace Venta\Framework;

use Monolog\Logger;
use Venta\Container\Container;
use Venta\Event\Dispatcher;

/**
 * Class Application
 *
 * @package Venta\Framework
 */
abstract class Application extends Skeleton
{
    /**
     * Application base path holder
     *
     * @var string
     */
    protected $_basePath;

    /**
     * Extensions file path holder
     *
     * @var string
     */
    protected $_extensionsFile;

    /**
     * Construct function
     *
     * @param  string $basePath
     * @param  string $extensionsFilePath
     */
    public function __construct(string $basePath, string $extensionsFilePath = 'bootstrap/extensions.php')
    {
        $this->_basePath = realpath($basePath);
        $this->_extensionsFile = $extensionsFilePath;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->share('app', $this);
        $this->share('events.dispatcher', new Dispatcher);
        $this->share('logger', new Logger('default'));

        $this->setEventsDispatcher($this->make('events.dispatcher'));
    }
}