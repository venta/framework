<?php
namespace Venta\Contracts\Mail;

use Swift_Transport;
/**
 * Class TransportFactory
 *
 * @package Venta\Contracts\Mail
 */
interface TransportFactory
{
    /**
     * @return Swift_Transport
     */
    public function getTransport(): Swift_Transport;
}