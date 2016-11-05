<?php declare(strict_types = 1);

namespace Venta\Mail;

use Venta\Contracts\Mail\Mailer as MailerContract;
use Swift_Mailer;
use Swift_Message;

/**
 * Class Mailer
 *
 * @package Venta\Mail
 */
class Mailer implements MailerContract
{
    const SPOOL_SEND_EVENT = 'swiftmailer.spool.send';

    private $disabled = false;

    private $swift;

    /**
     * Mailer constructor.
     *
     * @param Swift_Mailer $swiftMailer
     */
    public function __construct(Swift_Mailer $swiftMailer)
    {
        $this->swift = $swiftMailer;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param $message
     * @return array
     */
    public function send($message)
    {
        if ($message instanceof \Closure){
            return $this->sendClosure($message);
        }

        if ($message instanceof Mailable) {
            return $this->sendMailable($message);
        }

        $this->swift->send($message);
    }

    /**
     * @param \Closure $closure
     * @return array
     */
    protected function sendClosure(\Closure $closure)
    {
        $failed = [];
        $message = $this->getMessageInstance();
        $closure($message);
        $this->swift->send($message, $failed);

        return $failed;
    }

    /**
     * @param Mailable $mailable
     */
    protected function sendMailable(Mailable $mailable)
    {
        return $mailable->send($this);
    }

    /**
     * @return Message
     */
    protected function getMessageInstance()
    {
        return new Message();
    }

}