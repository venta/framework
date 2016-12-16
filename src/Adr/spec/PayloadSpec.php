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
        $payload->input()->shouldContain('input');
        $this->input()->shouldBeEmpty();
    }

    function it_has_immutable_output()
    {
        $payload = $this->withOutput('output');
        $payload->output()->shouldBe('output');
        $this->input()->shouldBeEmpty();
    }

    function it_has_status()
    {
        $this->status()->shouldBe('status');
    }

    function it_is_initializable()
    {
        $this->shouldImplement(\Venta\Contracts\Adr\Payload::class);
    }
}
