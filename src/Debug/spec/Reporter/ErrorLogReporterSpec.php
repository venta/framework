<?php

namespace spec\Venta\Debug\Reporter;

use Error;
use Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Venta\Contracts\Debug\ErrorReporter;

class ErrorLogReporterSpec extends ObjectBehavior
{
    function let(LoggerInterface $logger)
    {
        $this->beConstructedWith($logger);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ErrorReporter::class);
    }

    function it_reports_error(LoggerInterface $logger, Error $e)
    {
        $this->report($e);
        $logger->log(LogLevel::CRITICAL, Argument::type('string'), Argument::type('array'))->shouldHaveBeenCalled();
    }

    function it_reports_exception(LoggerInterface $logger, Exception $e)
    {
        $this->report($e);
        $logger->log(LogLevel::ERROR, Argument::type('string'), Argument::type('array'))->shouldHaveBeenCalled();
    }
}
