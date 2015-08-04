<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendService\Microsoft\Mpns\Message;

use ZendService\Microsoft\Mpns\Message;
use ZendService\Microsoft\Exception;

/**
 * Message Raw Object.
 */
class Raw extends Message
{
    /**
     * Mpns delays.
     *
     * @var int
     */
    const DELAY_IMMEDIATE = 3;
    const DELAY_450S = 13;
    const DELAY_900S = 23;

    /**
     * Message Body.
     *
     * @var string|null
     */
    protected $body;

    /**
     * Constructor.
     *
     * @param string $token
     * @param string $body
     * @param int    $delay
     *
     * @return Raw
     */
    public function __construct($token = null, $body = null, $delay = null)
    {
        if ($token !== null) {
            $this->setToken($token);
        }
        if ($body !== null) {
            $this->setBody($body);
        }
        if ($delay !== null) {
            $this->setDelay($delay);
        }
    }

    /**
     * Get Body.
     *
     * @return string|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set Body.
     *
     * @param string|null $body
     *
     * @return Alert
     */
    public function setBody($body)
    {
        if (!is_null($body) && !is_scalar($body)) {
            throw new Exception\InvalidArgumentException('Body must be null OR a scalar value');
        }
        if (!simplexml_load_string($body)) {
            throw new Exception\InvalidArgumentException('Body must be valid xml');
        }
        $this->body = $body;

        return $this;
    }

    /**
     * Get Notification Type.
     *
     * @return string
     */
    public static function getNotificationType()
    {
        return 'raw';
    }

    /**
     * To Payload
     * Get raw xml.
     *
     * @return array|string
     */
    public function getPayloadXml()
    {
        return $this->body;
    }
}
