<?php

namespace spec\Venta\Adr;

use PhpSpec\ObjectBehavior;

class PayloadSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('status');
    }

    public function getMatchers()
    {
        return [
            'beEmpty' => function ($subject) {
                return empty($subject);
            },
        ];
    }

    function it_has_immutable_input()
    {
        $payload = $this->withInput(['input']);
        $payload->getInput()->shouldContain('input');
        $this->getInput()->shouldBeEmpty();
    }

    function it_has_immutable_output()
    {
        $payload = $this->withOutput('output');
        $payload->getOutput()->shouldBe('output');
        $this->getInput()->shouldBeEmpty();
    }

    function it_has_status()
    {
        $this->getStatus()->shouldBe('status');
    }

    function it_is_initializable()
    {
        $this->shouldImplement(\Venta\Contracts\Adr\Payload::class);
    }
}
