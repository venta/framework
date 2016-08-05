<?php declare(strict_types = 1);

namespace Abava\Http;

/**
 * Cookie class
 *
 * @package Abava\Http
 */
class Cookie implements Contract\Cookie
{
    protected $name;
    protected $value;
    protected $domain;
    protected $expire;
    protected $path;
    protected $secure;
    protected $httpOnly;
    
    public function asPlainText()
    {
        return $this->__toString();
    }
    
    /**
     * Cookie constructor.
     * @param        $name
     * @param null   $value
     * @param int    $expire
     * @param string $path
     * @param null   $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     */
    public function __construct(
        $name,
        $value = null,
        $expire = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = true
    )
    {
        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new \InvalidArgumentException(sprintf('The cookie name "%s" contains invalid characters.', $name));
        }
        
        if (empty($name)) {
            throw new \InvalidArgumentException('The cookie name cannot be empty.');
        }
        
        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->format('U');
        } elseif (!is_numeric($expire)) {
            $expire = strtotime($expire);
            
            if (false === $expire || -1 === $expire) {
                throw new \InvalidArgumentException('The cookie expiration time is not valid.');
            }
        }
        
        $this->name = $name;
        $this->value = $value;
        $this->domain = $domain;
        $this->expire = $expire;
        $this->path = empty($path) ? '/' : $path;
        $this->secure = (bool) $secure;
        $this->httpOnly = (bool) $httpOnly;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        $str = urlencode($this->getName()) .'=';
        
        if('' === (string) $this->getValue()) {
           $str .= 'deleted; expires=' .self::inOutdated();
        } else {
            $str .= urlencode($this->getValue());
            
            if ($this->getExpireTime() !== 0) {
                $str .='; expires=' .$this->getExpireTime();
            }
        }
        
        if ($this->path) {
            $str .= '; path=' .$this->path;
        }
        
        if ($this->getDomain()) {
            $str .= '; domain=' .$this->getDomain();
        }
    
        if (true === $this->isSecure()) {
            $str .= '; secure';
        }
    
        if (true === $this->isHttpOnly()) {
            $str .= '; httponly';
        }
        
        return (string)$str;
    }
    
    /**
     * @param $minutes
     * @return int timestamp
     */
    public static function inMinutes($minutes)
    {
        return (new \DateTime('now'))->add(new \DateInterval('PT'.$minutes.'M'))->getTimestamp();
    }
    
    /**
     * @param $hours
     * @return int timestamp
     */
    public static function inHours($hours)
    {
        return (new \DateTime('now'))->add(new \DateInterval('PT'.$hours.'H'))->getTimestamp();
    }
    
    /**
     * @param $days
     * @return int timestamp
     */
    public static function inDays($days)
    {
        return (new \DateTime('now'))->add(new \DateInterval('P'.$days.'D'))->getTimestamp();
    }
    
    /**
     * @param $days
     * @return int timestamp
     */
    public static function inWeeks($days)
    {
        $weeks = $days * 7;
        return (new \DateTime('now'))->add(new \DateInterval('P'. $weeks .'D'))->getTimestamp();
    }
    
    /**
     * @param $months
     * @return int timestamp
     */
    public static function inMonths($months)
    {
        return (new \DateTime('now'))->add(new \DateInterval('P'. $months .'M'))->getTimestamp();
    }
    
    /**
     * @param $string
     * @return int timestamp
     */
    public static function inDateInterval($string)
    {
        if (is_string($string)) {
            return (new \DateTime('now'))->add(new \DateInterval($string))->getTimestamp();
        }
    }
    
    public static function inOutdated()
    {
        return (new \DateTime('now'))->sub(new \DateInterval('P12M'))->getTimestamp();
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getDomain()
    {
        return $this->domain;
    }
    
    public function getExpireTime()
    {
        return $this->expire;
    }
    
    public function isSecure()
    {
        return $this->secure;
    }
    
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }
}