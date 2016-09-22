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
     * Return new Swif_Message instance with applied default To and From
     *
     * @return \Swift_Message
     */
    public function getMessageBuilder(): \Swift_Message;

    /**
     * Is mailing disabled in config
     *
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * Return Swift_Mailer instance with specified transport
     *
     * @param string $transportName
     * @return \Swift_Mailer
     */
    public function withTransport(string $transportName): \Swift_Mailer;
}