<?php declare(strict_types = 1);

namespace Abava\Http;

use Abava\Http\Contract\Response as ResponseContract;
use Zend\Diactoros\Response as BaseResponse;

/**
 * Class Response
 *
 * @package Abava\Http
 */
class Response extends BaseResponse implements ResponseContract
{
    use ResponseTrait;
    
    /**
     * @param $cookies Cookie|Cookie[]
     * @return Response;
     */
    public function setCookies($cookies)
    {
        if ($cookies instanceof \Abava\Http\Contract\Cookie) {
            
            return $this->addCookie($cookies);
        } elseif (is_array($cookies)) {
            $response = $this;
            
            foreach ($cookies as $cookie) {
                /**
                 * @var $cookie Cookie
                 */
                if ($cookie instanceof \Abava\Http\Contract\Cookie) {
                    $response = $response->addCookie($cookie);
                } else {
                    throw new \InvalidArgumentException('Array must contain Cookie objects');
                }
            }
            
            return $response;
        }
    }
    
    protected function addCookie(Cookie $cookie)
    {
        return $this->withAddedHeader('Set-Cookie', $cookie->asPlainText());
    }
}