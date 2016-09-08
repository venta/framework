<?php declare(strict_types = 1);

namespace Abava\Config\Parser;

use Abava\Config\Config;
use Abava\Config\Contract\Config as ConfigContract;
use Abava\Config\Contract\Parser;

/**
 * Class Json
 *
 * @package Abava\Config\Parser
 */
class Json implements Parser
{
    /**
     * @inheritDoc
     */
    public function parse(string $configuration): ConfigContract
    {
        $array = json_decode($configuration, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                sprintf('Unable to parse configuration string: "%s".', json_last_error_msg()),
                json_last_error()
            );
        }

        return new Config($array);
    }
}