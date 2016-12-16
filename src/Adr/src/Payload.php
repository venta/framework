<?php declare(strict_types = 1);

namespace Venta\Adr;

use Venta\Contracts\Adr\Payload as PayloadContract;

/**
 * Class Payload
 *
 * @package Venta\Adr
 */
class Payload implements PayloadContract
{

    /**
     * @var array
     */
    private $input = [];

    /**
     * @var
     */
    private $output;

    /**
     * @var string
     */
    private $status = '';

    /**
     * Payload constructor.
     *
     * @param string $status
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }

    /**
     * @inheritDoc
     */
    public function input(): array
    {
        return $this->input;
    }

    /**
     * @inheritDoc
     */
    public function output()
    {
        return $this->output;
    }

    /**
     * @inheritDoc
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * Input setter.
     *
     * @param array $arguments
     * @return PayloadContract
     */
    public function withInput(array $arguments): PayloadContract
    {
        $payload = clone $this;
        $payload->input = $arguments;

        return $payload;
    }

    /**
     * Output setter.
     *
     * @param $output
     * @return PayloadContract
     */
    public function withOutput($output): PayloadContract
    {
        $payload = clone $this;
        $payload->output = $output;

        return $payload;
    }


}