<?php declare(strict_types = 1);

namespace Venta\Mail;

use Venta\Contracts\Mail\Mailer as MailerContract;
use Venta\Contracts\Mail\Mailable as MailableContract;
use Swift_Message;

//TODO: Replace Message with MessageContract

/**
 * Class Mailable
 *
 * @package Venta\Mail
 */
abstract class Mailable implements MailableContract
{
    public $attachments;

    public $bcc;

    public $body;

    public $cc;

    public $from;

    public $rawAttachments;

    public $returnPath;

    public $subject;

    public $to;

    /**
     * @param \Closure[] $callbacks
     */
    protected $callbacks;

    protected $mailer;

    /**
     * @var Swift_Message
     */
    protected $swift;

    /**
     * Mailable constructor.
     */
    public function __construct()
    {
        $this->swift = new Swift_Message();
    }

    /**
     * Rewrite this method in order to implement custom message builder
     *
     * @return mixed
     * @throws \Exception
     */
    public function build()
    {
        if ($this->mailer === null) {
            throw new \Exception('Mailer must be defined to build a message');
        }
    }

    /** @inheritdoc */
    public function send(MailerContract $mailer)
    {
        $this->mailer = $mailer;

        $mailer->send($this->toClosure());
    }

    /**
     * @return \Closure
     */
    protected function toClosure()
    {
        $mailable = $this;
        $mailable->build();

        return function ($message) use ($mailable) {
            $mailable->buildFrom($message)
                     ->buildTo($message)
                     ->buildCc($message)
                     ->buildBody($message)
                     ->buildBcc($message)
                     ->buildreturnPath($message)
                     ->buildAttachments($message)
                     ->runCallbacks($message);
        };
    }

    /**
     * @param Swift_Message $message
     * @return $this
     */
    protected function buildAttachments($message)
    {
        if ($this->attachments !== null) {
            foreach ($this->attachments as $attachment) {
                $message->attach($attachment['file'], $attachment['options']);
            }
        }

        return $this;
    }

    /**
     * @param Message $message
     * @return $this
     */
    protected function buildBcc($message)
    {
        $message->setBcc($this->bcc);

        return $this;
    }

    /**
     * @param $message Message
     * @return $this
     */
    protected function buildBody($message)
    {
        $message->setBody($this->body);

        return $this;
    }

    /**
     * @param Message $message;
     * @return $this;
     */
    protected function buildCc($message)
    {
        $message->setCc($this->cc);

        return $this;
    }

    /**
     * @param Message $message
     * @throws \Exception
     * @return $this
     */
    protected function buildFrom(Message $message)
    {
        if ($this->from === null) {
            throw new \Exception('From must be defined');
        }
        $message->setFrom($this->from);

        return $this;
    }

    /**
     * @param Message $message
     * @return $this
     */
    protected function buildReturnPath($message)
    {
        $message->setReturnPath($this->returnPath);

        return $this;
    }

    /**
     * @param Message $message
     * @return $this
     */
    protected function buildTo(Message $message)
    {
        $message->addTo($this->to);

        return $this;
    }

    /**
     * @param $message
     * @param Swift_Message $message
     * @return $this
     */
    protected function runCallbacks($message)
    {
        if ($this->callbacks !== null) {
            foreach ($this->callbacks as $callback) {
                $callback($message);
            }
        }

        return $this;
    }
}