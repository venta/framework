<?php declare(strict_types = 1);

class ErrorHandlerLoggerTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testHandle()
    {
        $exception = new Exception('Message');
        $logger = \Mockery::mock(\Psr\Log\LoggerInterface::class);
        $logger->shouldReceive('log')->once()->with(\Psr\Log\LogLevel::ERROR, 'Message', ['exception' => $exception]);
        $handler = new \Venta\Framework\ErrorHandler\ErrorHandlerLogger($logger);
        $handler->setException($exception);
        $result = $handler->handle();
        $this->assertSame($handler::DONE, $result);
    }

}