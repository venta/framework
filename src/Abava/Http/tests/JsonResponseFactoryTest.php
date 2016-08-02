<?php declare(strict_types = 1);

/**
 * Class JsonResponseFactoryTest
 */
class JsonResponseFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Abava\Http\Factory\JsonResponseFactory
     */
    protected $factory;
    
    public function setUp()
    {
        $this->factory = new \Abava\Http\Factory\JsonResponseFactory;
    }

    /**
     * @test
     */
    public function implementsJsonResponseFactoryContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\JsonResponseFactory::class, $this->factory);
    }

    /**
     * @test
     */
    public function canCreateJsonResponse()
    {
        $data = ['foo' => 'bar'];
        $response = $this->factory->createResponse($data);
        $this->assertInstanceOf(\Abava\Http\JsonResponse::class, $response);
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getBody()->__toString());
    }
}