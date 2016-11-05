<?php declare(strict_types = 1);

namespace Venta\Contracts\Mail;
use Venta\Contracts\Mail\Mailer;

/**
 * Interface Mailable
 */
interface Mailable
{
    /**
     * @param Mailer $mailer
     * @return mixed
     */
    public function send(Mailer $mailer);

    public function build();
}