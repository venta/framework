<?php declare(strict_types = 1);
/**
 * Class Mail
 *
 * @package Venta\Commands
 */

namespace Venta\Framework\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Console\Command;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Event\EventManager as EventManagerContract;


class Mail extends Command
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    public function description(): string
    {
        return 'Sends all queued e-mails';
    }

    public function handle(InputInterface $input, OutputInterface $output)
    {
        /** @var $em \Venta\Event\EventManager */
        $em = $this->container->get(EventManagerContract::class);

        /** @var $mailer \Venta\Mail\Mailer */
        $mailer = $this->container->get('mailer');

        $transport = $input->getOption('transport');
        $mailer->spoolWithTransport($transport);
        $em->trigger($mailer::SPOOL_SEND_EVENT);
    }

    public function signature(): string
    {
        return 'mailer:send {--transport=default:Specify transport Spool to be sent}';
    }
}