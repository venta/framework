<?php declare(strict_types = 1);

namespace Abava\Http;

use Abava\Http\Contract\Response as ResponseContract;

/**
 * Class Response
 *
 * @package Abava\Http
 */
class Response extends \Zend\Diactoros\Response implements ResponseContract
{
    /**
     * {@inheritdoc}
     */
    public function append(string $body): ResponseContract
    {
        $this->getBody()->write($body);
        return $this;
    }

}