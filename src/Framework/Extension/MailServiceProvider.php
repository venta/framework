<?php declare(strict_types = 1);

namespace Venta\Framework\Extension;

use Venta\Contracts\Mail\Mailer as MailerContract;
use Venta\Framework\Commands\Mail;
use Venta\Mail\Mailer;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class MailExtensionProvider
 *
 * @package Venta\Framework\Extension
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

        $this->container->share(MailerContract::class, Mailer::class, ['mailer']);

        $this->provideCommands(Mail::class);
    }
}