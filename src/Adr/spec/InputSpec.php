<?php

namespace spec\Venta\Adr;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Http\Request;

class InputSpec extends ObjectBehavior
{
    function let(Request $request)
    {
        $request->getQueryParams()->willReturn(['query' => 'value']);
        $request->getParsedBody()->willReturn(['body' => 'value']);
        $request->getUploadedFiles()->willReturn(['files' => 'value']);
        $request->getCookieParams()->willReturn(['cookie' => 'value']);
        $request->getAttributes()->willReturn(['attribute' => 'value']);
    }

    public function getMatchers()
    {
        return [
            'containKeys' => function ($subject, ...$keys) {
                foreach ($keys as $key) {
                    if (!array_key_exists($key, $subject[0])) {
                        return false;
                    }
                }

                return true;
            },
        ];
    }

    function it_converts_server_request_to_array(Request $request)
    {
        $this->process($request)->shouldContainKeys('query', 'body', 'files', 'cookie', 'attribute');
    }

    function it_is_initializable()
    {
        $this->shouldImplement(\Venta\Contracts\Adr\Input::class);
    }
}
