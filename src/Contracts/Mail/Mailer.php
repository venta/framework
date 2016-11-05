<?php declare(strict_types = 1);

namespace Venta\Contracts\Mail;

/**
 * Interface Mailer
 *
 * @package Venta\Contracts\Mail
 */
interface Mailer
{
    /**
     * Is mailing disabled in config
     *
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * @param $mailer
     * @return mixed
     */
    public function send($mailer);
}