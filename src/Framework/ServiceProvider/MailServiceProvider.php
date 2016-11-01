<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Venta\Contracts\Mail\Mailer as MailerContract;
use Venta\Framework\Commands\Mail;
use Venta\Mail\Mailer;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class MailServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
class MailServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->container->get('config')->push([
            'mailer' => [
                'transport' => 'mail',
                'spool' => [
                    'type' => 'file',
                    'path' => 'storage/spool',
                ],
            ],
        ]);

        $this->container->bindClass(MailerContract::class, Mailer::class, true);

        $this->provideCommands(Mail::class);
    }
}